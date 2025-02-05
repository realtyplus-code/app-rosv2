<template>
    <toggle-button
        v-model="checked"
        on-label="Kanban"
        off-label="Table"
    ></toggle-button>
    <component
        :is="checked ? 'incident-kanban-component' : 'incident-component'"
        :permissions="permissions"
    ></component>
</template>

<script>
export default {
    props: ["permissions"],
    name: "IncidentTemplateComponent",
    data() {
        return {
            checked: false,
        };
    },
};
</script>
