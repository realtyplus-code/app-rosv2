<script>
export default {
    methods: {
        $relationTableProperty(selector) {
            switch (selector) {
                case "name":
                    return "properties.name";
                case "address":
                    return "properties.address";
                case "property_type_name":
                    return "eo_property_type.name";
                case "status":
                    return "properties.status";
                default:
                    return selector;
            }
        },
    },
};
</script>
