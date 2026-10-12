#!/usr/bin/env python3
"""
Petsfolio Support AI Knowledge Base & RAG Engine
Separates and manages two distinct Knowledge Bases:
1. Client Knowledge Base ('client_support_kb.json' & Qdrant collection 'petsfolio_client_kb')
2. Service Provider Knowledge Base ('sp_support_kb.json' & Qdrant collection 'petsfolio_sp_kb')
"""

import os
import sys
import json
import re
import urllib.request
import urllib.error
import argparse

# Default Endpoints
QDRANT_HOST = os.getenv("QDRANT_HOST", "http://127.0.0.1:6333")
OLLAMA_HOST = os.getenv("OLLAMA_HOST", "http://127.0.0.1:11434")
EMBED_MODEL = os.getenv("EMBED_MODEL", "nomic-embed-text")

CLIENT_COLLECTION = "petsfolio_client_kb"
SP_COLLECTION = "petsfolio_sp_kb"

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
ADMIN_DIR = os.path.abspath(os.path.join(BASE_DIR, "..", ".."))
CLIENT_JSON_PATH = os.path.join(ADMIN_DIR, "app", "Libraries", "AIAnalyst", "client_support_kb.json")
SP_JSON_PATH = os.path.join(ADMIN_DIR, "app", "Libraries", "AIAnalyst", "sp_support_kb.json")
COMBINED_JSON_PATH = os.path.join(ADMIN_DIR, "app", "Libraries", "AIAnalyst", "support_kb.json")


def generate_embedding(text):
    """
    Generate 768-dim vector embedding using Ollama nomic-embed-text.
    """
    try:
        payload = json.dumps({
            "model": EMBED_MODEL,
            "prompt": text
        }).encode('utf-8')

        req = urllib.request.Request(
            f"{OLLAMA_HOST}/api/embeddings",
            data=payload,
            headers={"Content-Type": "application/json"}
        )
        with urllib.request.urlopen(req, timeout=15) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("embedding")
    except Exception as e:
        sys.stderr.write(f"Embedding error for prompt '{text[:30]}...': {e}\n")
        return None


def init_qdrant_collection(collection_name, vector_size=768):
    """
    Ensure Qdrant collection exists for specified collection name.
    """
    try:
        # Check existing collection
        req = urllib.request.Request(f"{QDRANT_HOST}/collections/{collection_name}")
        try:
            with urllib.request.urlopen(req, timeout=5) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                if data.get("result"):
                    return True
        except urllib.error.HTTPError as he:
            if he.code != 404:
                sys.stderr.write(f"Qdrant check error for '{collection_name}': {he}\n")

        # Create collection
        payload = json.dumps({
            "vectors": {
                "size": vector_size,
                "distance": "Cosine"
            }
        }).encode('utf-8')

        req = urllib.request.Request(
            f"{QDRANT_HOST}/collections/{collection_name}",
            data=payload,
            headers={"Content-Type": "application/json"},
            method="PUT"
        )
        with urllib.request.urlopen(req, timeout=10) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("result", False)
    except Exception as e:
        sys.stderr.write(f"Qdrant collection creation error for '{collection_name}': {e}\n")
        return False


def load_kb_chunks(json_path):
    """
    Load chunks directly from a JSON KB file.
    """
    if not os.path.exists(json_path):
        raise FileNotFoundError(f"Knowledge Base JSON not found at: {json_path}")
    with open(json_path, 'r', encoding='utf-8') as f:
        data = json.load(f)
    return data.get("chunks", [])


def index_chunks_to_qdrant(chunks, collection_name, verbose=True):
    """
    Generates embeddings for chunks and upserts points into specified Qdrant collection.
    """
    if not init_qdrant_collection(collection_name, 768):
        if verbose:
            sys.stderr.write(f"Failed to initialize Qdrant collection '{collection_name}'.\n")
        return False

    points = []
    for i, chunk in enumerate(chunks):
        embed_text = f"Title: {chunk['title']}\nCategory: {chunk['category']} > {chunk.get('subcategory', '')}\nKeywords: {', '.join(chunk.get('keywords', []))}\nContent: {chunk['content']}"
        vec = generate_embedding(embed_text)
        if vec:
            points.append({
                "id": i + 1,
                "vector": vec,
                "payload": {
                    "chunk_id": chunk["id"],
                    "title": chunk["title"],
                    "category": chunk["category"],
                    "subcategory": chunk.get("subcategory", ""),
                    "user_type": chunk.get("user_type", "both"),
                    "priority": chunk.get("priority", "Medium"),
                    "keywords": chunk.get("keywords", []),
                    "key_facts": chunk.get("key_facts", {}),
                    "content": chunk["content"]
                }
            })
        else:
            if verbose:
                sys.stderr.write(f"Failed to generate embedding for chunk '{chunk['id']}'\n")

    if not points:
        if verbose:
            sys.stderr.write(f"No points generated for indexing into '{collection_name}'.\n")
        return False

    payload = json.dumps({"points": points}).encode('utf-8')
    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{collection_name}/points",
        data=payload,
        headers={"Content-Type": "application/json"},
        method="PUT"
    )

    try:
        with urllib.request.urlopen(req, timeout=20) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            if verbose:
                print(f"Successfully indexed {len(points)} knowledge chunks into Qdrant collection '{collection_name}'!")
            return True
    except Exception as e:
        if verbose:
            sys.stderr.write(f"Error upserting points to Qdrant '{collection_name}': {e}\n")
        return False


def query_qdrant_kb(query_text, user_type='client', top_k=3):
    """
    Semantic search in Qdrant for top matching knowledge chunks in dedicated collection.
    """
    collection_name = SP_COLLECTION if (user_type in ['provider', 'service_provider']) else CLIENT_COLLECTION
    query_vec = generate_embedding(query_text)
    if not query_vec:
        return []

    search_payload = {
        "vector": query_vec,
        "limit": top_k,
        "with_payload": True
    }

    payload = json.dumps(search_payload).encode('utf-8')
    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{collection_name}/points/search",
        data=payload,
        headers={"Content-Type": "application/json"}
    )

    try:
        with urllib.request.urlopen(req, timeout=8) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            results = res.get("result", [])
            matches = []
            for item in results:
                payload_data = item.get("payload", {})
                matches.append({
                    "title": payload_data.get("title", ""),
                    "category": payload_data.get("category", ""),
                    "subcategory": payload_data.get("subcategory", ""),
                    "priority": payload_data.get("priority", "Medium"),
                    "content": payload_data.get("content", ""),
                    "key_facts": payload_data.get("key_facts", {}),
                    "score": item.get("score", 0)
                })
            return matches
    except Exception as e:
        sys.stderr.write(f"Search query error on '{collection_name}': {e}\n")
        return []


def sync_combined_kb():
    """
    Combines Client and SP KBs into support_kb.json for backward compatibility.
    """
    client_chunks = load_kb_chunks(CLIENT_JSON_PATH) if os.path.exists(CLIENT_JSON_PATH) else []
    sp_chunks = load_kb_chunks(SP_JSON_PATH) if os.path.exists(SP_JSON_PATH) else []
    
    combined = {
        "version": "1.0.0",
        "description": "Unified Petsfolio Knowledge Base (Client + Service Provider)",
        "total_chunks": len(client_chunks) + len(sp_chunks),
        "client_chunks_count": len(client_chunks),
        "sp_chunks_count": len(sp_chunks),
        "chunks": client_chunks + sp_chunks
    }
    
    with open(COMBINED_JSON_PATH, 'w', encoding='utf-8') as f:
        json.dump(combined, f, indent=2, ensure_ascii=False)
    print(f"Synced unified Knowledge Base to: {COMBINED_JSON_PATH}")


def main():
    parser = argparse.ArgumentParser(description="Petsfolio Support AI Knowledge Base Ingester & RAG Search")
    parser.add_argument("--kb", type=str, default="all", choices=["client", "provider", "all"], help="Which Knowledge Base to index or query")
    parser.add_argument("--index", action="store_true", help="Index selected Knowledge Base(s) into Qdrant")
    parser.add_argument("--query", type=str, help="Run semantic query search against knowledge base")
    parser.add_argument("--top-k", type=int, default=3, help="Number of results to retrieve")
    args = parser.parse_args()

    # Always sync combined JSON
    sync_combined_kb()

    if args.index:
        if args.kb in ["client", "all"]:
            print(f"\nIndexing Client KB ({CLIENT_JSON_PATH}) into '{CLIENT_COLLECTION}'...")
            client_chunks = load_kb_chunks(CLIENT_JSON_PATH)
            index_chunks_to_qdrant(client_chunks, CLIENT_COLLECTION, verbose=True)

        if args.kb in ["provider", "all"]:
            print(f"\nIndexing Service Provider KB ({SP_JSON_PATH}) into '{SP_COLLECTION}'...")
            sp_chunks = load_kb_chunks(SP_JSON_PATH)
            index_chunks_to_qdrant(sp_chunks, SP_COLLECTION, verbose=True)

    if args.query:
        user_type = "provider" if args.kb == "provider" else "client"
        print(f"\nSearching Knowledge Base for: '{args.query}' (KB Target: {user_type.upper()}, Top: {args.top_k})\n")
        results = query_qdrant_kb(args.query, user_type=user_type, top_k=args.top_k)
        if not results:
            print("No matching knowledge base chunks found.")
        else:
            for idx, r in enumerate(results, 1):
                print(f"--- [Result {idx}] (Score: {r['score']:.4f}) ---")
                print(f"Title: {r['title']} [{r['category']} > {r.get('subcategory', '')}]")
                print(f"Priority: {r.get('priority', 'Medium')}")
                print(f"Content Preview:\n{r['content'][:250]}...\n")


if __name__ == "__main__":
    main()
