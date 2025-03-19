import walletService from "@/services/walletService";
import { useCrud } from "@/composables/useCrud.js";

export function useWallet() {
    return useCrud(walletService, {});
}
