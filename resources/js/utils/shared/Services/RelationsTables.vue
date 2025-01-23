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
                case "country":
                    return "ec.name";
                case "state":
                    return "es.name";
                case "city":
                    return "eci.name";
                case "created_at":
                    return "properties.created_at";
                case "expected_end_date_ros":
                    return "properties.expected_end_date_ros";
                default:
                    return selector;
            }
        },
        $relationTableUser(selector) {
            switch (selector) {
                case "name":
                    return "users.name";
                case "user":
                    return "users.user";
                case "email":
                    return "users.email";
                case "phone":
                    return "users.phone";
                case "address":
                    return "users.address";
                case "country":
                    return "ec.name";
                case "state":
                    return "es.name";
                case "city":
                    return "eci.name";
                case "language_name":
                    return "el.name";
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
                case "policy_number":
                    return "insurances.policy_number";
                case "start_date":
                    return "insurances.start_date";
                case "end_date":
                    return "insurances.end_date";
                case "insurance_name":
                    return "e_ct.name";
                case "property_name":
                    return "properties.name";
                case "contact_person":
                    return "insurances.contact_person";
                case "contact_email":
                    return "insurances.contact_email";
                case "country_name":
                    return "ec.name";
                case "position":
                    return "insurances.position";
                case "phone":
                    return "insurances.phone";
                case "created_at":
                    return "insurances.created_at";
                case "updated_at":
                    return "insurances.updated_at";
                case "policy_amount":
                    return "insurances.policy_amount";
                case "renewal_months":
                    return "insurances.renewal_months";
                case "renewal_date":
                    return "insurances.renewal_date";
                default:
                    return selector;
            }
        },

        $relationTableIncident(selector) {
            switch (selector) {
                case "description":
                    return "incidents.description";
                case "report_date":
                    return "incidents.report_date";
                case "status_name":
                    return "eo_status.name";
                case "incident_type_name":
                    return "eo_incident_type.name";
                case "priority_name":
                    return "eo_priority.name";
                case "currency_name":
                    return "e_cur.name";
                default:
                    return selector;
            }
        },
        $relationTableIncidentAction(selector) {
            switch (selector) {
                case "incident_name":
                    return "incidents.description";
                case "action_description":
                    return "incident_actions.action_description";
                case "action_date":
                    return "incident_actions.action_date";
                case "cost":
                    return "incident_actions.action_cost";
                case "user_name":
                    return "users.name";
                case "provider_name":
                    return "providers.name";
                case "currency_name":
                    return "ec.name";
                case "status_name":
                    return "es.name";
                case "action_type_name":
                    return "eat.name";
                case "log_user_name":
                    return "user_log.name";
                default:
                    return selector;
            }
        },
        $relationTableProvider(selector) {
            switch (selector) {
                case "name":
                    return "providers.name";
                case "address":
                    return "providers.address";
                case "coverage_area":
                    return "providers.coverage_area";
                case "contact_phone":
                    return "providers.contact_phone";
                case "contact_email":
                    return "providers.contact_email";
                case "service_cost":
                    return "providers.service_cost";
                case "status":
                    return "providers.status";
                default:
                    return selector;
            }
        },
    },
};
</script>
