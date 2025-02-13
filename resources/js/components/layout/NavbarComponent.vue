<template>
    <Menubar class="custom-menubar">
        <template #start>
            <Button
                class="menu-button"
                icon="pi pi-bars"
                aria-label="Filter"
                @click="toggleDrawer"
                raised
            />
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
    <DrawerComponent
        :visibleMenu="visibleMenu"
        :permissions="permissions"
        @closeDrawer="hiddenMenu"
    />
</template>

<script>
// Importar Librerias o Modulos
import Menubar from "primevue/menubar";
import DrawerComponent from "./DrawerComponent.vue";

export default {
    props: ["userAuth", "permissions"],
    data() {
        return {
            labelUser: null,
            visibleMenu: false,
            rolName: null,
            logoPath: `${window.location.origin}/img/rentalcolorb.svg`,
            menuItems: [
                {
                    label: "",
                    icon: "pi pi-user",
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
        DrawerComponent,
    },
    created() {
        this.labelUser =
            "Bienvenido " +
            (this.userAuth.roles && this.userAuth.roles.length > 0
                ? this.roleAlias(this.userAuth.roles[0].name)
                : "") +
            " " +
            this.userAuth.name;
        this.menuItems[0].label = this.labelUser;
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
        toggleDrawer() {
            this.visibleMenu = !this.visibleMenu;
        },
        hiddenMenu() {
            this.visibleMenu = false;
        },
        roleAlias(roleName) {
            const aliases = {
                owner: "Propietario",
                tenant: "Inquilino",
                provider: "Proveedor",
                ros_client: "Cliente Ros",
                ros_client_manager: "Gerente Cliente Ros",
                global_manager: "Gerente Global",
            };
            return aliases[roleName] || roleName;
        },
    },
};
</script>

<style scoped>
.menu-button {
    background-color: #f76f31;
    border: none;
}

.menu-button:hover {
    background-color: #fb8047 !important;
    border: none !important;
}

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
