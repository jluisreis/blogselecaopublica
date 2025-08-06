const toggleBtn = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const searchToggle = document.getElementById("search-toggle");
const searchBox = document.getElementById("search-box");

toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    toggleBtn.classList.toggle("active");
});

overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    toggleBtn.classList.remove("active");
    searchBox.classList.remove("active");
});

searchToggle.addEventListener("click", () => {
    searchBox.classList.toggle("active");
});

searchInput.addEventListener('input', () => {
    const termo = searchInput.value.toLowerCase();
    const noticias = document.querySelectorAll('.news-item');

    noticias.forEach(noticia => {
        const titulo = noticia.querySelector('.news-title').textContent.toLowerCase();
        const descricao = noticia.querySelector('.news-description').textContent.toLowerCase();
        const corresponde = titulo.includes(termo) || descricao.includes(termo);

        noticia.style.display = corresponde ? 'flex' : 'none';
    });
});