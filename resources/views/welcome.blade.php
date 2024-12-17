<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nested Sortable Menu</title>
    <style>
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            padding: 8px;
            margin: 4px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            cursor: grab;
        }

        ul ul {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <h1>Nested Sortable Menu</h1>
    <button onclick="saveMenu()">Simpan Menu</button>

    <!-- Tampilkan Menu dengan Data-ID -->
    <ul id="nested-sortable">
        @foreach ($menus as $menu)
            <li data-id="{{ $menu->id }}">{{ $menu->name }}
                @if ($menu->children->count())
                    <ul>
                        @foreach ($menu->children as $child)
                            <li data-id="{{ $child->id }}">{{ $child->name }}
                                @if ($child->children->count())
                                    <ul>
                                        @foreach ($child->children as $child)
                                            <li data-id="{{ $child->id }}">{{ $child->name }}
                                                @include('partials.menu-children', [
                                                    'children' => $child->children,
                                                ])
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        // Inisialisasi SortableJS
        const nestedLists = document.querySelectorAll('#nested-sortable, #nested-sortable ul');
        nestedLists.forEach(list => {
            new Sortable(list, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65
            });
        });

        // Fungsi untuk mengonversi DOM menjadi struktur JSON
        function getNestedData(parent) {
            const items = [];
            parent.querySelectorAll(':scope > li').forEach((li, index) => {
                const children = li.querySelector('ul');
                const item = {
                    id: li.dataset.id, // Ambil data-id dari elemen
                    name: li.firstChild.textContent.trim(), // Nama menu
                    order: index + 1, // Urutan berdasarkan posisi
                    children: children ? getNestedData(children) : []
                };
                items.push(item);
            });
            return items;
        }

        // Simpan menu ke server
        function saveMenu() {
            const menuData = getNestedData(document.getElementById('nested-sortable'));

            fetch('/menus/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        menus: menuData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert('Menu berhasil disimpan!');
                    console.log(data);
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>
