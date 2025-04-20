import fixedExpenseTemplateService from "@/services/fixedExpenseTemplateService.js";
import { messageResponse } from "@/utils/messageResponse";

export function useHandlerFixedExpense(emit) {

    const { handleApiResponse, handleApiError } = messageResponse();

    const assignTemplate = async (template, month) => {
        if (!confirm(`Assign template "${template.name}" for ${month}?`)) return false;

        try {
            const response = await fixedExpenseTemplateService.assign(template.id, { month });

            if (response.data.message === 'Template assigned successfully') {
                template.isAssigned = true;
                handleApiResponse(response);
                if (emit) emit('assigned');
                return true;
            } else {
                handleApiError(response, response.data.message);
                return false;
            }
        } catch (error) {
            handleApiError(error, "Failed to assign template.");
            console.error('Error assigning template:', error);
            return false;
        }
    };

    return {
        assignTemplate,
    }
}
