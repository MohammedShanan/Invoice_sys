import { defineStore } from "pinia";

export const useInvoiceStore = defineStore("invoiceStore", {
  state: () => ({
    invoices: [],
    invoiceType: "sales",
  }),

  actions: {
    async fetchInvoices(type = "sales") {
          this.invoiceType = type;
          console.log("from store")
          console.log(this.invoiceType)
      try {
        const response = await fetch(
          `http://127.0.0.1:8000/api/invoices/${type}`
        );
        if (!response.ok) throw new Error("Failed to fetch invoices");
        this.invoices = await response.json();
      } catch (error) {
        console.error("Error fetching invoices:", error);
      }
    },
  },
});
