import { ref, computed } from "vue";

export function useTransactionFilter(transactions) {
    const filterCriteria = ref({
        dateRange: null,
        categoryId: "",
        walletId: ""
    });

    const updateFilterCriteria = (criteria) => {
        filterCriteria.value = criteria;
    };

    const filteredTransactions = computed(() => {
        return transactions.value.filter(transaction => {
            const { dateRange, categoryId, walletId } = filterCriteria.value;

            const matchDate = !dateRange || (
                new Date(transaction.transaction_date) >= new Date(dateRange[0]) &&
                new Date(transaction.transaction_date) <= new Date(dateRange[1])
            );

            const matchCategory = !categoryId || transaction.category.id === categoryId;
            const matchWallet = !walletId || transaction.wallet.id === walletId;

            return matchDate && matchCategory && matchWallet;
        });
    });


    return { filterCriteria, updateFilterCriteria, filteredTransactions };
}
