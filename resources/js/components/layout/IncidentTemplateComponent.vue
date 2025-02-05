<template>
    <toggle-button
        v-model="checked"
        on-label="Kanban"
        off-label="Table"
    ></toggle-button>
    <component
        :is="checked ? 'incident-kanban-component' : 'incident-component'"
        :role="role"
        :permissions="permissions"
    ></component>
</template>

<script>
export default {
    props: ["role", "permissions"],
    name: "IncidentTemplateComponent",
    data() {
        return {
            checked: false,
        };
    },
};
</script>
