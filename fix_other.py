import re

# Fix Analytics
file_path = 'resources/views/homeadminS/shortlink/analytics.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

content = content.replace(
    '<link rel="stylesheet" href="{{ asset(\'css/pages/shortlink-analytics.css\') }}">',
    '<link rel="stylesheet" href="{{ asset(\'css/pages/shortlink-analytics.css\') }}" data-turbo-track="reload">'
)
content = content.replace('<div class="page-shortlink-analytics">\n', '')
content = content.replace('</div>\n@endsection', '@endsection')

with open(file_path, 'w') as f:
    f.write(content)

# Fix Orders
file_path = 'resources/views/homeadminS/orders.blade.php'
with open(file_path, 'r') as f:
    content = f.read()

content = content.replace(
    '<link rel="stylesheet" href="{{ asset(\'css/pages/orders.css\') }}">',
    '<link rel="stylesheet" href="{{ asset(\'css/pages/orders.css\') }}" data-turbo-track="reload">'
)
content = content.replace('<div class="page-orders-view">\n', '')
content = content.replace('        </div>\n@endsection', '@endsection')

with open(file_path, 'w') as f:
    f.write(content)
print("Done fixing others")
