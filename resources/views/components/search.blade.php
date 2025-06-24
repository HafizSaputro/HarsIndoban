<div class="mb-4 flex justify-left">
    <input
        type="text"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        class="w-80 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    />
</div>

<script>
    document.getElementById('{{ $id }}').addEventListener('input', function () {
        const query = this.value;

        fetch(`{{ $action }}?search=${query}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('{{ $tableId }}');
                tableBody.innerHTML = '';

                data.forEach(item => {
                    const row = `
                        <tr>
                            ${Object.values(item).map(value => `<td class="p-4 border">${value}</td>`).join('')}
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            });
    });
</script>
