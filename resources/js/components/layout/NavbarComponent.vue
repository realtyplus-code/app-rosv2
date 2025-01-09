<template>
    <Menubar class="custom-menubar">
        <template #start>
            <img
                :src="logoPath"
                alt="Logo"
                class="navbar-logo"
                @click="route('home')"
            />
        </template>
        <template #end>
            <div class="menu-items">
                <Menubar :model="menuItems" />
            </div>
        </template>
    </Menubar>
    <br /><br />
</template>

<script>
// Importar Librerias o Modulos

import Menubar from "primevue/menubar";

export default {
    props: [],
    data() {
        return {
            rolName: null,
            logoPath: `${window.location.origin}/img/rentalcolorb.svg`,
            menuItems: [
                {
                    label: "Properties",
                    icon: "pi pi-warehouse",
                    items: [
                        {
                            label: "Property",
                            icon: "pi pi-home",
                            command: () => {
                                this.route("property");
                            },
                        },
                        {
                            label: "Insurance",
                            icon: "pi pi-shield",
                            command: () => {
                                this.route("insurance");
                            },
                        },
                        /*  {
                            label: "Incident Kanban BETA!!!",
                            icon: "pi pi-exclamation-triangle",
                            command: () => {
                                this.route("occurrences-kanban");
                            },
                        }, */
                    ],
                },
                {
                    label: "Incidents",
                    icon: "pi pi-exclamation-circle",
                    items: [
                        {
                            label: "Incident",
                            icon: "pi pi-exclamation-triangle",
                            command: () => {
                                this.route("occurrences");
                            },
                        },
                        {
                            label: "Actions incident",
                            icon: "pi pi-wrench",
                            command: () => {
                                this.route("occurrences-action");
                            },
                        },
                    ],
                },
                {
                    label: "Admin",
                    icon: "pi pi-users",
                    items: [
                        {
                            label: "User",
                            icon: "pi pi-user",
                            command: () => {
                                this.route("user");
                            },
                        },
                        {
                            label: "Provider",
                            icon: "pi pi-truck",
                            command: () => {
                                this.route("provider");
                            },
                        },
                        {
                            label: "Enum",
                            icon: "pi pi-cog",
                            command: () => {
                                this.route("enum");
                            },
                        },
                    ],
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
                case "property":
                    window.location.href = "/properties";
                    break;
                case "enum":
                    window.location.href = "/enums";
                    break;
                case "home":
                    window.location.href = "/home";
                    break;
                case "insurance":
                    window.location.href = "/insurances";
                    break;
                case "occurrences":
                    window.location.href = "/occurrences";
                    break;
                case "occurrences-kanban":
                    window.location.href = "/occurrences/kanban";
                    break;
                case "occurrences-action":
                    window.location.href = "/occurrences-action";
                    break;
                case "provider":
                    window.location.href = "/providers";
                    break;
                default:
                    break;
            }
        },
    },
};
</script>

<style scoped>
.navbar-logo {
    width: 180px !important;
    margin-left: 20px !important;
    cursor: pointer;
}

.p-menubar {
    border: none !important;
}

.swal2-confirm {
    background-color: #f76f31;
}
</style>

<style>
.menu-items {
    position: relative;
}

.menu-items .p-menubar-mobile .p-menubar-root-list {
    position: absolute !important;
    left: -160px !important;
    width: 400% !important;
    padding: 10px 20px !important;
}

.menu-items .p-menubar-mobile .p-menubar-submenu {
    background-color: rgb(255, 255, 255) !important;
    position: relative !important;
    right: 15px !important;
}
</style>
