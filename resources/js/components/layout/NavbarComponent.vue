<template>
    <Menubar class="custom-menubar">
        <template #start>
            <img
                :src="logoPath"
                alt="Logo"
                class="navbar-logo"
                style="max-width: 150px !important"
            />
            <p class="custom-message">{{ rolMessage }}</p>
        </template>
        <template #end>
            <div class="menu-items">
                <Menubar :model="menuItems" class="custom-check" />
            </div>
        </template>
    </Menubar>
    <br /><br />
</template>

<script>
// Importar Librerias o Modulos

import Menubar from "primevue/menubar";

export default {
    props: ["rol"],
    data() {
        return {
            rolName: null,
            rolMessage: null,
            logoPath: `${window.location.origin}/img/logo.jpg`,
            menuItems: [
                {
                    label: "Property",
                    icon: "pi pi-home",
                    command: () => {
                        this.route("home");
                    },
                },
                {
                    label: "User",
                    icon: "pi pi-user",
                    command: () => {
                        this.route("user");
                    },
                },
                {
                    label: "Enum",
                    icon: "pi pi-cog",
                    command: () => {
                        this.route("enum");
                    },
                },
                /* {
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
                    ],
                }, */
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
    created() {
        if (Array.isArray(this.rol) && this.rol.length > 0) {
            this.rolName = this.rol[0];
            this.rolMessage = this.getWelcomeMessage(this.rolName);
        }
    },
    mounted() {},
    methods: {
        async logout() {
            try {
                const result = await this.$swal.fire({
                    title: "You're sure?",
                    text: "Your session will be closed.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, log out",
                    cancelButtonText: "Cancel",
                });
                if (result.isConfirmed) {
                    await this.$axios.post("/logout");
                    window.location.href = "/";
                }
            } catch (error) {
                this.$readStatusHttp(error);
            }
        },
        route(key) {
            switch (key) {
                case "user":
                    window.location.href = "/users";
                    break;
                case "home":
                    window.location.href = "/home";
                    break;
                case "enum":
                    window.location.href = "/enums";
                    break;
                default:
                    break;
            }
        },
        getWelcomeMessage(userRole) {
            let message;
            switch (userRole) {
                case "owner":
                    message = "Welcome back, Property Owner!";
                    break;
                case "tenant":
                    message = "Hello, Tenant! Welcome to your dashboard.";
                    break;
                case "admin":
                    message = "Welcome, Admin! You have full access.";
                    break;
                case "providers":
                    message = "Hello, Provider! Welcome to your dashboard.";
                    break;
                default:
                    message = "Welcome! Please check your profile.";
                    break;
            }
            return message;
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
