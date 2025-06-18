setTimeout(() => {
    const msg = document.getElementById('mensagem-flash');
    if (msg) {
        msg.style.transition = 'opacity 0.5s ease';
        msg.style.opacity = 0;

        // Remove do DOM após transição (opcional)
        setTimeout(() => msg.remove(), 500);
    }
}, 2000); // 10 segundos = 10000 milissegundos