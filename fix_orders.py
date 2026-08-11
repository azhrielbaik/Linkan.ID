import re
file_path = 'resources/views/homeadminS/orders.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

content = content.replace(
    '<link rel="stylesheet" href="{{ asset(\'css/pages/orders.css\') }}">',
    '<link rel="stylesheet" href="{{ asset(\'css/pages/orders.css\') }}" data-turbo-track="reload">'
)
with open(file_path, 'w') as f:
    f.write(content)
