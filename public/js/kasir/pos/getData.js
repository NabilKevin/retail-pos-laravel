const search = document.getElementById('search')

const updateTableObat = (data, isSkeleton) => {
  let contentObats = ''

  if(!isSkeleton) {
    if(data.length > 0) {
      data.forEach(d => {
        contentObats += `
          <div class="flex items-center justify-between px-4 pb-3 py-4 my-4">
            <div>
              <div class="font-medium">${d.nama}</div>
              <div class="font-medium mb-2">${formatNumber(d.harga)}</div>
              <div class="text-xs text-neutral-500">Stok: ${d.stok}</div>
            </div>
            <button data-id="${d.id}" class="btn-tambah text-sm px-3 py-1.5 rounded-md border border-neutral-200 dark:border-neutral-800 hover:bg-neutral-50 dark:hover:bg-neutral-800">Tambah</button>
          </div>
        `
      })
    } else {
      contentObats += '<h1 class="text-center mt-3 mb-6 font-medium alert-text-table">Produk tidak ditemukan!</h1>'
    }
  } else {
    contentObats += `
      <div class="animate-pulse space-y-2">
        ${[...Array(3)].map(() => `
          <div class="flex items-center justify-between px-4 py-8 border-b">
            <div class="flex-1">
                <div class="h-4 bg-gray-300 rounded w-1/3 mb-2"></div>
                <div class="h-3 bg-gray-200 rounded w-1/4"></div>
            </div>
            <div class="h-4 bg-gray-300 rounded w-16"></div>
          </div>  
        `)}
      </div>
    `
  }

  tableObats.innerHTML = contentObats 
}

const fetchDataObat = async () => {
  try {
    const res = await fetch(`${BASE_URL}obat/search?q=${search.value}`);
    const data = await res.json();
    updateTableObat(data, false);
  } catch(e) {
    console.error(e);
  }
}

function getDataObat() {
  updateTableObat([], true) 
  fetchDataObat()
}

search.addEventListener('change', getDataObat)