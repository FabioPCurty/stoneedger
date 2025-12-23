import os
from dotenv import load_dotenv

load_dotenv()

url = os.getenv("SUPABASE_URL")
key = os.getenv("SUPABASE_KEY")

print(f"URL loaded: {url is not None}")
if url:
    print(f"URL type: {type(url)}")
    print(f"URL length: {len(url)}")
    print(f"URL repr: {repr(url)}")
    print(f"URL starts with https://: {url.startswith('https://')}")

print(f"KEY loaded: {key is not None}")
if key:
    print(f"KEY length: {len(key)}")
