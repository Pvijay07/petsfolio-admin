#!/usr/bin/env python3
"""
Qdrant RAG Vector Store Module for Petsfolio Client <-> Service Provider Call Analysis.
Integrates Qdrant vector database (http://127.0.0.1:6333) with Ollama nomic-embed-text
embeddings to index and retrieve service conversation rules from client_provider_call_guidelines.txt.
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
COLLECTION_NAME = "client_call_analysis_rules"


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
<<<<<<< HEAD
        with urllib.request.urlopen(req, timeout=0.5) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("embedding")
    except Exception:
=======
        with urllib.request.urlopen(req, timeout=15) as resp:
            res = json.loads(resp.read().decode('utf-8'))
            return res.get("embedding")
    except Exception as e:
>>>>>>> origin/main
        return None


def init_qdrant_collection(vector_size=768):
    """
    Ensure Qdrant collection 'client_call_analysis_rules' exists. Create if missing.
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


def chunk_client_guidelines_file(filepath):
    """
    Parse client_provider_call_guidelines.txt into structured section chunks.
    """
    if not os.path.exists(filepath):
        sys.stderr.write(f"Client guidelines file not found at: {filepath}\n")
        return []

    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

<<<<<<< HEAD
    # Split by section headers e.g. "1. Document Purpose", "4. Supported Services", etc.
=======
    # Split by section headers e.g. "1. Document Purpose", "4.1 Walking Analysis", "13.1 Normal GST Inquiries", etc.
>>>>>>> origin/main
    raw_sections = re.split(r'\n(?=\d+(?:\.\d+)?\.\s+)', content)
    chunks = []
    
    for i, sec in enumerate(raw_sections):
        sec_clean = sec.strip()
        if not sec_clean:
            continue

        lines = sec_clean.split('\n')
        title = lines[0].strip() if lines else f"Section {i+1}"
        text_body = "\n".join(lines[1:]).strip() if len(lines) > 1 else sec_clean

        # Identify tags
        tags = []
        sec_lower = sec_clean.lower()
        if 'walk' in sec_lower: tags.append('walking')
        if 'groom' in sec_lower: tags.append('grooming')
        if 'train' in sec_lower: tags.append('training')
        if 'sit' in sec_lower: tags.append('sitting')
        if 'payment' in sec_lower or 'off-platform' in sec_lower: tags.append('off_platform_payment')
        if 'contact' in sec_lower or 'phone' in sec_lower or 'whatsapp' in sec_lower: tags.append('contact_sharing')
<<<<<<< HEAD
        if 'complaint' in sec_lower or 'refund' in sec_lower: tags.append('complaint')
=======
        if 'bypass' in sec_lower or 'skip petsfolio' in sec_lower or 'book direct' in sec_lower: tags.append('platform_bypass')
        if 'gst' in sec_lower or 'platform charge' in sec_lower or 'platform fee' in sec_lower: tags.append('gst_platform_charges')
        if 'false' in sec_lower or 'over-highlight' in sec_lower or 'do not highlight' in sec_lower: tags.append('false_positive_prevention')
        if 'severity' in sec_lower or 'level 1' in sec_lower or 'level 4' in sec_lower: tags.append('severity_levels')
        if 'complaint' in sec_lower: tags.append('complaint')
        if 'refund' in sec_lower: tags.append('refund')
        if 'inappropriate' in sec_lower or 'abusive' in sec_lower: tags.append('abuse_inappropriate_language')
        if 'threat' in sec_lower or 'intimidat' in sec_lower: tags.append('threatening_communication')
        if 'cancel' in sec_lower: tags.append('cancellation')
        if 'reschedul' in sec_lower: tags.append('rescheduling')
        if 'outside-service' in sec_lower or 'additional service' in sec_lower: tags.append('additional_service')
        if 'pet' in sec_lower or 'bruno' in sec_lower or 'temperament' in sec_lower or 'nervous' in sec_lower: tags.append('pet_context')
>>>>>>> origin/main
        if 'rating' in sec_lower: tags.append('rating')

        chunks.append({
            "id": i + 1,
            "title": title,
            "content": sec_clean,
            "body": text_body,
            "tags": tags
        })

    return chunks


<<<<<<< HEAD
def index_client_guidelines_to_qdrant(filepath=None, verbose=False):
=======
def recreate_qdrant_collection(vector_size=768):
    """
    Delete and recreate collection to ensure fresh indexing.
    """
    try:
        req = urllib.request.Request(
            f"{QDRANT_HOST}/collections/{COLLECTION_NAME}",
            method="DELETE"
        )
        with urllib.request.urlopen(req, timeout=5) as resp:
            pass
    except Exception:
        pass
    return init_qdrant_collection(vector_size)


def index_client_guidelines_to_qdrant(filepath=None, verbose=False, recreate=True):
>>>>>>> origin/main
    """
    Read client_provider_call_guidelines.txt, embed sections, and store in Qdrant collection.
    """
    if filepath is None:
        base_dir = os.path.dirname(os.path.abspath(__file__))
        filepath = os.path.join(base_dir, "client_provider_call_guidelines.txt")

    chunks = chunk_client_guidelines_file(filepath)
    if not chunks:
        return False

    first_vec = get_embedding("Petsfolio Client Provider Call Analysis Vector Test")
    if not first_vec:
        if verbose:
            sys.stderr.write("Failed to get initial embedding vector from Ollama nomic-embed-text.\n")
        return False

    vector_size = len(first_vec)
<<<<<<< HEAD
    if not init_qdrant_collection(vector_size):
=======
    if recreate:
        if not recreate_qdrant_collection(vector_size):
            if verbose:
                sys.stderr.write("Could not recreate Qdrant collection.\n")
            return False
    elif not init_qdrant_collection(vector_size):
>>>>>>> origin/main
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
                    "body": chunk["body"],
                    "tags": chunk["tags"]
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
                sys.stderr.write(f"Successfully indexed {len(points)} client-provider guideline chunks into Qdrant collection '{COLLECTION_NAME}'.\n")
            return True
    except Exception as e:
        if verbose:
            sys.stderr.write(f"Error upserting points to Qdrant: {e}\n")
        return False


def query_client_qdrant_guidelines(query_text, service_type='walking', top_k=4):
    """
    Search Qdrant collection 'client_call_analysis_rules' for relevant service conversation rules.
    Auto-indexes client_provider_call_guidelines.txt into Qdrant if collection does not exist (404).
    Returns list of matched guideline text payloads with similarity scores.
    """
    query_vec = get_embedding(f"[{service_type}] {query_text}")
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
                    "tags": payload_data.get("tags", []),
                    "score": score
                })
            return matches
    except urllib.error.HTTPError as he:
        if he.code == 404:
            # Collection does not exist in Qdrant yet; index it now
            indexed = index_client_guidelines_to_qdrant()
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
                                "tags": payload_data.get("tags", []),
                                "score": score
                            })
                        return matches
                except Exception:
                    pass
        return []
    except Exception:
        return []


if __name__ == "__main__":
    sys.stdout.write(f"Indexing client-provider call guidelines to Qdrant collection '{COLLECTION_NAME}'...\n")
    success = index_client_guidelines_to_qdrant(verbose=True)
    if success:
        sys.stdout.write("Verification query test against Qdrant...\n")
        results = query_client_qdrant_guidelines("walking bruno leash paw cleaning off-platform direct payment complaint", service_type="walking")
        sys.stdout.write(f"Retrieved {len(results)} matching rules:\n")
        for r in results:
            sys.stdout.write(f"- [{r['score']:.3f}] {r['title']} (Tags: {', '.join(r.get('tags', []))})\n")
    else:
        sys.stderr.write("Failed to index guidelines to Qdrant.\n")
