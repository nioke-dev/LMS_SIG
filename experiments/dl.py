import urllib.request
import certifi
import ssl

url = "https://contribution.usercontent.google.com/download?c=CgthaWRhX2NvZGVmeBJ8Eh1hcHBfY29tcGFuaW9uX2dlbmVyYXRlZF9maWxlcxpbCiVodG1sXzE4OTgxN2M1MzgzMzQ5M2E4NmZhM2Q1MTc4NTdjZTA1EgsSBxDAmbDx7AwYAZIBJAoKcHJvamVjdF9pZBIWQhQxNjMxNzEzMTQ2NDI2MDcwNTU1NQ&filename=&opi=89354086"
context = ssl.create_default_context(cafile=certifi.where())

try:
    response = urllib.request.urlopen(url, context=context)
    html = response.read().decode('utf-8')
    with open('landing_page_stitch.html', 'w', encoding='utf-8') as f:
        f.write(html)
    print("Success")
except Exception as e:
    print(f"Error: {e}")
