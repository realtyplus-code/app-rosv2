<template>
    <Drawer v-model:visible="visible" class="custom-drawer" @hide="closeDrawer">
        <template #header>
            <div class="flex items-center gap-2">
                <Avatar
                    image="/img/rentalcolorb.svg"
                    style="width: 80%; justify-content: center; cursor: pointer"
                    @click="route('home')"
                />
            </div>
        </template>
        <h6>Main</h6>
        <hr />
        <ul class="menu-options">
            <li v-for="item in items" :key="item.label">
                <div
                    v-if="getPermissionsByRoles(item.permissions)"
                    @click="item.expanded = !item.expanded"
                    class="menu-headers"
                >
                    <i :class="item.icon + ' p-menuitem-icon'"></i>
                    <span class="p-menuitem-text">{{ item.label }}</span>
                    <i
                        class="pi pi-chevron-down"
                        v-if="item.items"
                        style="float: right"
                    ></i>
                </div>
                <ul v-if="item.items && item.expanded" class="submenu-options">
                    <li v-for="subItem in item.items" :key="subItem.label">
                        <a
                            v-if="getPermissionsByRole(subItem.permission)"
                            href="#"
                            @click="subItem.command"
                            style="text-decoration: none"
                        >
                            <i :class="subItem.icon + ' p-menuitem-icon'"></i>
                            <span class="p-menuitem-text">{{
                                subItem.label
                            }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </Drawer>
</template>

<script>
export default {
    props: ["visibleMenu", "permissions"],
    emits: ["closeDrawer"],
    data() {
        return {
            visible: this.visibleMenu,
            items: [
                {
                    label: "Properties",
                    icon: "pi pi-warehouse",
                    permissions: ["list_properties", "list_insurances"],
                    expanded: false,
                    items: [
                        {
                            label: "Property",
                            icon: "pi pi-home",
                            permission: "list_properties",
                            command: () => {
                                this.route("property");
                            },
                        },
                        {
                            label: "Insurance",
                            icon: "pi pi-shield",
                            permission: "list_insurances",
                            command: () => {
                                this.route("insurance");
                            },
                        },
                    ],
                },
                {
                    label: "Incidents",
                    icon: "pi pi-exclamation-circle",
                    permissions: ["list_incidents", "list_incidents_actions"],
                    expanded: false,
                    items: [
                        {
                            label: "Incident",
                            icon: "pi pi-exclamation-triangle",
                            permission: "list_incidents",
                            command: () => {
                                this.route("occurrences");
                            },
                        },
                        {
                            label: "Actions incident",
                            icon: "pi pi-wrench",
                            permission: "list_incidents_actions",
                            command: () => {
                                this.route("occurrences-action");
                            },
                        },
                    ],
                },
                {
                    label: "Management",
                    icon: "pi pi-users",
                    permissions: ["list_users", "list_providers", "list_enums"],
                    expanded: false,
                    items: [
                        {
                            label: "User",
                            icon: "pi pi-user",
                            permission: "list_users",
                            command: () => {
                                this.route("user");
                            },
                        },
                        {
                            label: "Provider",
                            icon: "pi pi-truck",
                            permission: "list_providers",
                            command: () => {
                                this.route("provider");
                            },
                        },
                        {
                            label: "Enum",
                            icon: "pi pi-cog",
                            permission: "list_enums",
                            command: () => {
                                this.route("enum");
                            },
                        },
                    ],
                },
            ],
            notificationVisible: false,
            cantNotifications: 0,
        };
    },
    watch: {
        visibleMenu(newVal) {
            this.visible = newVal;
        },
    },
    methods: {
        closeDrawer() {
            this.$emit("closeDrawer");
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
        getPermissionsByRole(name) {
            return this.permissions.some(
                (permission) => permission.name == name
            );
        },
        getPermissionsByRoles(permissions) {
            return permissions.some((permission) =>
                this.permissions.some((p) => p.name == permission)
            );
        },
    },
};
</script>

<style scoped>
.menu-options .p-menuitem-icon {
    margin-right: 10px;
    color: #f76f31;
}
.menu-options {
    padding: 10px;
    margin: 0;
    list-style: none;
}
.menu-options .menu-headers {
    cursor: pointer;
    margin-top: 5px;
    padding: 10px;
}
.submenu-options {
    border-radius: 10px;
    padding: 5px;
    background-color: #e5e5e5;
    list-style: none;
}
.submenu-options li {
    padding: 10px;
    transition: padding 0.3s ease;
}
.submenu-options li:hover {
    padding: 12px;
}
</style>
