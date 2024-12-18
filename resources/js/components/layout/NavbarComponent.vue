<template>
    <Menubar class="custom-menubar">
        <template #start>
            <img src="img/Logo.png" alt="Logo" class="navbar-logo" />
        </template>
        <template #end>
            <div class="menu-items">
                <Menubar :model="menuItems" class="custom-check" />
            </div>
        </template>
    </Menubar>
</template>

<script>
// Importar Librerias o Modulos

import Menubar from "primevue/menubar";

export default {
    props: [],
    data() {
        return {
            menuItems: [
                {
                    label: "Home",
                    icon: "pi pi-home",
                },
                {
                    label: "Features",
                    icon: "pi pi-star",
                },
                {
                    label: "Projects",
                    icon: "pi pi-search",
                    items: [
                        {
                            label: "Components",
                            icon: "pi pi-bolt",
                        },
                        {
                            label: "Blocks",
                            icon: "pi pi-server",
                        },
                        {
                            label: "UI Kit",
                            icon: "pi pi-pencil",
                        },
                        {
                            label: "Templates",
                            icon: "pi pi-palette",
                            items: [
                                {
                                    label: "Apollo",
                                    icon: "pi pi-palette",
                                },
                                {
                                    label: "Ultima",
                                    icon: "pi pi-palette",
                                },
                            ],
                        },
                    ],
                },
                {
                    label: "Contact",
                    icon: "pi pi-envelope",
                },
                {
                    label: "Logout",
                    icon: "pi pi-sign-out",
                    command: () => {
                        this.logout();
                    },
                },
            ],
        };
    },
    components: {
        Menubar,
    },
    created() {},
    mounted() {},
    methods: {
        async logout() {
            try {
                const result = await this.$swal.fire({
                    title: "¿Estás seguro?",
                    text: "Tu sesión será cerrada.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, cerrar sesión",
                    cancelButtonText: "Cancelar",
                });
                if (result.isConfirmed) {
                    await this.$axios.post("/logout");
                    window.location.href = "/";
                }
            } catch (error) {
                this.$readStatusHttp(error);
            }
        },
    },
};
</script>

<style scoped>
.navbar-logo {
    max-width: 80px !important;
    margin-left: 20px !important;
}

.p-menubar {
    border: none !important;
}
</style>
