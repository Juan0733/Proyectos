<header>
    <nav id="navigation-menu">
        <div class="content-menu-list">

            <h3 class="menu-item menu-section-title">Menú</h3>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="home" class="menu-link">
                        <ion-icon name="home-outline" class="menu-icon"></ion-icon> 
                        <span>Inicio</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="menu-link">
                        <ion-icon name="cart-outline" class="menu-icon"></ion-icon> 
                        <span>Pedidos</span>
                    </a>
                </li>
                <li class="menu-item">

                    <a href="productos" class="menu-link">
                        <ion-icon name="restaurant-outline" class="menu-icon"></ion-icon>
                        <span>Productos</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?php echo $url_variable; ?>mesas" class="menu-link">
                        <ion-icon name="grid-outline" class="menu-icon"></ion-icon>
                        <span>Mesas</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="categorias" class="menu-link">
                        <ion-icon name="folder-outline" class="menu-icon"></ion-icon>
                        <span>Inventario</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="ingredientes" class="menu-link">
                        <ion-icon name="people-outline" class="menu-icon"></ion-icon>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="informes.php" class="menu-link">
                        <ion-icon name="document-outline" class="menu-icon"></ion-icon>
                        <span>Informes</span>
                    </a>
                </li>
            </ul>

            <!-- Ajustes -->
            <ul>
                <h3 class="menu-item menu-section-title">Ajustes</h3>
                <li class="menu-item">
                    <a href="perfil.php" class="menu-link">
                        <ion-icon name="person-outline" class="menu-icon"></ion-icon>
                        <span>Mi Perfil</span>
                    </a>
                </li>
            </ul>

        </div>

        <div class="content-menu-help">
            <div class="item-help">
                <ion-icon name="help-circle-outline"></ion-icon>
                <span>¿Necesitas ayuda?</span>
            </div>
        </div>
    </nav>
</header>

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
        const menuLinks = document.querySelectorAll('.menu-link');
        const activeClass = 'active';

        
        function loadContent(view) {
            
            fetch('app/views/content/' + view + '-view.php')
                .then(response => response.text())
                .then(data => {
                    document.querySelector('#contenedor_pagina_principal').innerHTML = data;
                    // Al cargar una nueva vista, actualizar el enlace activo
                    localStorage.setItem('activeMenu', view);
                    updateActiveMenu(view);
                })
                .catch(error => console.log('Error al cargar la vista:', error));
        }

    
        function updateActiveMenu(view) {
            menuLinks.forEach(link => {
                const linkView = link.getAttribute('href').replace('.php', ''); // Eliminar la extensión
                if (linkView === view) {
                    link.classList.add(activeClass);
                } else {
                    link.classList.remove(activeClass);
                }
            });
        }

        // Verificar si existe un enlace activo en localStorage
        const savedActiveUrl = localStorage.getItem('activeMenu');
        if (savedActiveUrl) {
            updateActiveMenu(savedActiveUrl);
            loadContent(savedActiveUrl);
        }

        // Asignar eventos de clic para cambiar las vistas
        menuLinks.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const view = link.getAttribute('href').replace('.php', ''); // Obtener el nombre de la vista sin la extensión
                loadContent(view);
            });
        });
    });
</script> -->