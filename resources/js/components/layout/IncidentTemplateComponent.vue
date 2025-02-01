<template>
    <toggle-button
        v-model="checked"
        on-label="Kanban"
        off-label="Table"
    ></toggle-button>
    <component
        :is="checked ? 'incident-kanban-component' : 'incident-component'"
    ></component>
</template>

<script>
export default {
    name: "IncidentTemplateComponent",
    data() {
        return {
            checked: false,
        };
    },
};
</script>
