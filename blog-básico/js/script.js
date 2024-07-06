document.addEventListener('DOMContentLoaded', () => {
    let page = 1;
    const articlesContainer = document.getElementById('articles');
    const loadMoreButton = document.getElementById('loadMore');

    loadMoreButton.addEventListener('click', () => {
        page++;
        fetch(`load_more_articles.php?page=${page}`)
            .then(response => response.json())
            .then(data => {
                data.articles.forEach(article => {
                    const articleDiv = document.createElement('div');
                    articleDiv.className = 'article';
                    articleDiv.innerHTML = `
                        <h2>${article.title}</h2>
                        <p>${article.content.substring(0, 100)}...</p>
                        <img src="${article.image}" alt="Article Image">
                    `;
                    articlesContainer.appendChild(articleDiv);
                });
                if (page * 12 >= data.totalArticles) {
                    loadMoreButton.style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
