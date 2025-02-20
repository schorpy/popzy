const tabs = document.querySelectorAll('[role="tab"]');
const panels = document.querySelectorAll('[role="tabpanel"]');

// Fungsi untuk mengaktifkan tab
function activateTab(tab) {
    // Nonaktifkan semua tab dan sembunyikan semua panel
    tabs.forEach(t => {
        t.setAttribute("aria-selected", "false");
        t.classList.remove("bg-sky-500/50", "text-white");
        t.classList.add("bg-gray-200", "text-gray-400");
    });

    panels.forEach(p => {
        p.hidden = true;
        p.classList.remove("animate-fade"); // Reset animasi sebelum menambahkannya kembali
    });

    // Aktifkan tab yang diklik dan tampilkan panel yang sesuai
    tab.setAttribute("aria-selected", "true");
    tab.classList.add("bg-sky-500/50", "text-white");
    tab.classList.remove("bg-gray-200", "text-gray-400");

    const panel = document.getElementById(tab.getAttribute("aria-controls"));
    panel.hidden = false; // Tampilkan panel

    // Gunakan setTimeout untuk memastikan animasi dipicu setelah elemen terlihat
    setTimeout(() => {
        panel.classList.add("animate-fade");
    }, 10);
}

// Tetapkan tab default saat halaman dimuat
const defaultTab = document.querySelector('[role="tab"][aria-selected="true"]');
if (defaultTab) activateTab(defaultTab);
else activateTab(tabs[0]); // Pilih tab pertama jika tidak ada yang aktif

// Event listener untuk klik tab
tabs.forEach(tab => {
    tab.addEventListener("click", (event) => {
        event.preventDefault();
        event.stopPropagation();
        activateTab(tab);
    });
});

