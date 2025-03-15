import toastr from "toastr";
export function messageResponse() {
    const getApiMessage = (response, defaultMessage = "Operation successful!") => {
        return response?.data?.message || defaultMessage;
    };

    const handleApiResponse = (response) => {
        const message = getApiMessage(response, "Success!");
        toastr.success(message);
    };

    const handleApiError = (error, defaultMessage = "Something went wrong!") => {
        const message = error.response?.data?.message || defaultMessage;
        toastr.error(message);
        console.error("API Error:", message, error);
    };

    return { handleApiResponse, handleApiError };
}
