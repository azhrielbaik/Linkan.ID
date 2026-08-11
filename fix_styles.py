import re

def fix_blade(file_path, css_path, track_reload=True):
    with open(file_path, 'r') as f:
        content = f.read()

    # Find the style block within @push("styles")
    pattern = re.compile(r'(@push\("styles"\)\s*<style>).*?(</style>\s*@endpush)', re.DOTALL)
    
    match = pattern.search(content)
    if match:
        css_content = content[match.start(1) + len(match.group(1)):match.start(2)].strip()
        with open(css_path, 'w') as f:
            f.write(css_content)
        
        attr = ' data-turbo-track="reload"' if track_reload else ''
        replacement = f'@push("styles")\n<link rel="stylesheet" href="{{{{ asset(\'{css_path.replace("public/", "")}\') }}}}"{attr}>\n@endpush'
        new_content = pattern.sub(replacement, content, count=1)

        with open(file_path, 'w') as f:
            f.write(new_content)

fix_blade('resources/views/homeadminS/shortlink/create.blade.php', 'public/css/pages/shortlink-create.css', True)
fix_blade('resources/views/homeadminS/shortlink/analytics.blade.php', 'public/css/pages/shortlink-analytics.css', True)
print("Done")
