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
        $relationTableUser(selector) {
            switch (selector) {
                case "name":
                    return "users.name";
                case "email":
                    return "users.email";
                case "phone":
                    return "users.phone";
                default:
                    return selector;
            }
        },
        $relationTableEnumOption(selector) {
            switch (selector) {
                default:
                    return selector;
            }
        },
        $relationTableInsurance(selector) {
            switch (selector) {
                case "insurance_company":
                    return "insurances.insurance_company";
                case "start_date":
                    return "insurances.start_date";
                case "end_date":
                    return "insurances.end_date";
                case "contact_person":
                    return "insurances.contact_person";
                case "coverage_name":
                    return "e_ct.name";
                case "property_name":
                    return "properties.name";
                case "created_at":
                    return "insurances.created_at";
                case "updated_at":
                    return "insurances.updated_at";
                default:
                    return selector;
            }
        },
    },
};
</script>
