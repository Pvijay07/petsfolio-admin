#!/usr/bin/env python3
"""
Qdrant RAG Vector Store Module for Petsfolio Call Analysis.
Integrates Qdrant vector database (http://127.0.0.1:6333) with Ollama nomic-embed-text
embeddings to index and retrieve business guidelines from call_analysis_guidelines.txt.
"""

import os
import sys
import json
import re
import urllib.request
import urllib.error

QDRANT_HOST = os.getenv("QDRANT_HOST", "http://127.0.0.1:6333")
OLLAMA_HOST = os.getenv("OLLAMA_HOST", "http://127.0.0.1:11434")
EMBED_MODEL = os.getenv("EMBED_MODEL", "nomic-embed-text")
COLLECTION_NAME = "call_analysis_rules"


def get_embedding(text):
    """
    Generate vector embedding using Ollama nomic-embed-text API.
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
        sys.stderr.write(f"Error generating embedding for text snippet: {e}\n")
        return None


def init_qdrant_collection(vector_size=768):
    """
    Ensure Qdrant collection exists. Create if not already created.
    """
    try:
        # Check if collection exists
        req = urllib.request.Request(f"{QDRANT_HOST}/collections/{COLLECTION_NAME}")
        try:
            with urllib.request.urlopen(req, timeout=5) as resp:
                data = json.loads(resp.read().decode('utf-8'))
                if data.get("result"):
                    return True
        except urllib.error.HTTPError as he:
            if he.code != 404:
                sys.stderr.write(f"Qdrant collection check HTTP error: {he}\n")

        # Create collection if 404 or missing
        payload = json.dumps({
            "vectors": {
                "size": vector_size,
                "distance": "Cosine"
            }
        }).encode('utf-8')

        req = urllib.request.Request(
            f"{QDRANT_HOST}/collections/{COLLECTION_NAME}",
            data=payload,
            headers={"Content-Type": "application/json"},
            method="PUT"
        )
        with urllib.request.urlopen(req, timeout=10) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("result", False)
    except Exception as e:
        sys.stderr.write(f"Error initializing Qdrant collection '{COLLECTION_NAME}': {e}\n")
        return False


def chunk_guidelines_file(filepath):
    """
    Parse call_analysis_guidelines.txt into structured section chunks.
    """
    if not os.path.exists(filepath):
        sys.stderr.write(f"Guidelines file not found at: {filepath}\n")
        return []

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Split by section headers e.g. "1. Objective", "2. Call Analysis", etc.
    raw_sections = re.split(r'\n(?=\d+\.\s+)', content)
    chunks = []
    
    for i, sec in enumerate(raw_sections):
        sec_clean = sec.strip()
        if not sec_clean:
            continue

        lines = sec_clean.split('\n')
        title = lines[0].strip() if lines else f"Section {i+1}"
        text_body = "\n".join(lines[1:]).strip() if len(lines) > 1 else sec_clean

        chunks.append({
            "id": i + 1,
            "title": title,
            "content": sec_clean,
            "body": text_body
        })

    return chunks


def index_guidelines_to_qdrant(filepath=None, verbose=False):
    """
    Read call_analysis_guidelines.txt, embed sections, and store in Qdrant.
    """
    if filepath is None:
        base_dir = os.path.dirname(os.path.abspath(__file__))
        filepath = os.path.join(base_dir, "call_analysis_guidelines.txt")

    chunks = chunk_guidelines_file(filepath)
    if not chunks:
        return False

    first_vec = get_embedding("Petsfolio Call Analysis Vector Test")
    if not first_vec:
        if verbose:
            sys.stderr.write("Failed to get initial embedding vector from Ollama nomic-embed-text.\n")
        return False

    vector_size = len(first_vec)
    if not init_qdrant_collection(vector_size):
        if verbose:
            sys.stderr.write("Could not initialize Qdrant collection.\n")
        return False

    points = []
    for chunk in chunks:
        vec = get_embedding(chunk["content"])
        if vec:
            points.append({
                "id": chunk["id"],
                "vector": vec,
                "payload": {
                    "title": chunk["title"],
                    "content": chunk["content"],
                    "body": chunk["body"]
                }
            })

    if not points:
        if verbose:
            sys.stderr.write("No points generated for indexing.\n")
        return False

    payload = json.dumps({"points": points}).encode('utf-8')
    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{COLLECTION_NAME}/points",
        data=payload,
        headers={"Content-Type": "application/json"},
        method="PUT"
    )

    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            if verbose:
                sys.stderr.write(f"Successfully indexed {len(points)} guideline chunks into Qdrant.\n")
            return True
    except Exception as e:
        if verbose:
            sys.stderr.write(f"Error upserting points to Qdrant: {e}\n")
        return False


def query_qdrant_guidelines(query_text, top_k=4):
    """
    Search Qdrant collection for relevant call analysis rules based on query.
    Auto-indexes call_analysis_guidelines.txt into Qdrant if collection does not exist (404).
    Returns list of matched guideline text payloads.
    """
    query_vec = get_embedding(query_text)
    if not query_vec:
        return []

    payload = json.dumps({
        "vector": query_vec,
        "limit": top_k,
        "with_payload": True
    }).encode('utf-8')

    req = urllib.request.Request(
        f"{QDRANT_HOST}/collections/{COLLECTION_NAME}/points/search",
        data=payload,
        headers={"Content-Type": "application/json"}
    )

    try:
        with urllib.request.urlopen(req, timeout=5) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            results = res.get("result", [])
            matches = []
            for item in results:
                payload_data = item.get("payload", {})
                score = item.get("score", 0)
                matches.append({
                    "title": payload_data.get("title", ""),
                    "content": payload_data.get("content", ""),
                    "score": score
                })
            return matches
    except urllib.error.HTTPError as he:
        if he.code == 404:
            # Collection does not exist in Qdrant yet; index it now
            indexed = index_guidelines_to_qdrant()
            if indexed:
                try:
                    with urllib.request.urlopen(req, timeout=5) as resp:
                        res = json.loads(resp.read().decode('utf-8'))
                        results = res.get("result", [])
                        matches = []
                        for item in results:
                            payload_data = item.get("payload", {})
                            score = item.get("score", 0)
                            matches.append({
                                "title": payload_data.get("title", ""),
                                "content": payload_data.get("content", ""),
                                "score": score
                            })
                        return matches
                except Exception:
                    pass
        return []
    except Exception:
        return []


if __name__ == "__main__":
    sys.stdout.write("Indexing guidelines to Qdrant...\n")
    success = index_guidelines_to_qdrant(verbose=True)
    if success:
        sys.stdout.write("Verification query test against Qdrant...\n")
        results = query_qdrant_guidelines("unrelated conversation cricket music song bad language rating criteria")
        sys.stdout.write(f"Retrieved {len(results)} matching rules:\n")
        for r in results:
            sys.stdout.write(f"- [{r['score']:.3f}] {r['title']}\n")
    else:
        sys.stderr.write("Failed to index guidelines to Qdrant.\n")
