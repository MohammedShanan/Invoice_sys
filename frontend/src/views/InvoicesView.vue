<template>
  <div class="container mt-4">
    <h2 class="text-capitalize">{{ title }}</h2>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="btn-group">
        <button class="btn btn-primary" @click="fetchInvoices('sales')">
          Sales Invoices
        </button>
        <button class="btn btn-danger" @click="fetchInvoices('returns')">
          Return Invoices
        </button>
      </div>

      <RouterLink :to="'/create/' + invoiceType" class="btn btn-success">
        + Create Invoice
      </RouterLink>
    </div>

    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Date</th>
          <th>Representative</th>
          <th>Invoice Type</th>
          <th>Discount</th>
          <th>Tax</th>
          <th>Final Total</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="invoice in invoices" :key="invoice.id">
          <td>{{ invoice.id }}</td>
          <td>{{ invoice.date }}</td>
          <td>{{ invoice.representative }}</td>
          <td>{{ invoice.invoice_type }}</td>
          <td>
            {{ invoice.discount_value }} ({{ invoice.discount || "N/A" }})
          </td>
          <td>{{ invoice.tax }}</td>
          <td>{{ invoice.final_total }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { useInvoiceStore } from "../stores/invoiceStore";
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
export default {
  setup() {
    const invoiceStore = useInvoiceStore();
    const { invoices, invoiceType } = storeToRefs(invoiceStore);
    const title = ref("sales Invoices");
    const fetchInvoices = (type) => {
      title.value = `${type} Invoices`;
      invoiceStore.fetchInvoices(type);
    };
    onMounted(() => {
      invoiceStore.fetchInvoices("sales");
    });
    return {
      invoices,
      invoiceType,
      fetchInvoices,
      title,
    };
  },
};
</script>
