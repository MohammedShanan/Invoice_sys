<template>
  <div class="container mt-4">
    <h2>Create {{ invoiceType === "sales" ? "Sales" : "Return" }} Invoice</h2>

    <div class="card mb-3">
      <div class="card-header">Invoice Details</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <label class="form-label">Date</label>
            <input
              v-model="invoice.date"
              type="date"
              class="form-control"
              required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Representative</label>
            <input
              v-model="invoice.representative"
              type="text"
              class="form-control"
              required />
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Client Information</div>
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-5 position-relative">
            <label class="form-label">Client Name</label>
            <div class="d-flex gap-2">
              <input
                v-if="clientMode === 'search'"
                v-model="searchQuery"
                type="text"
                class="form-control"
                @focus="showDropdown = true"
                @blur="hideDropdown"
                placeholder="Search client" />
              <input
                v-else
                v-model="invoice.client.name"
                type="text"
                class="form-control"
                placeholder="Add new client" />

              <select
                v-model="clientMode"
                class="form-select"
                style="width: auto">
                <option value="search">Search</option>
                <option value="add">Add New</option>
              </select>
            </div>

            <ul
              v-if="showDropdown && clientMode === 'search'"
              class="list-group mt-2 position-absolute w-100 dropdown-menu"
              @mousedown.prevent>
              <li
                v-for="client in searchResults"
                :key="client.id"
                class="list-group-item"
                @click="selectClient(client)">
                {{ client.name }}
              </li>
            </ul>
          </div>

          <div class="col-md-auto">
            <label class="form-label">Client Type</label>
            <select
              v-model="invoice.client.type"
              class="form-select"
              style="width: auto">
              <option value="business">Business</option>
              <option value="person">Person</option>
            </select>
          </div>
        </div>

        <div class="row mt-2">
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input
              v-model="invoice.client.email"
              type="email"
              class="form-control" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input
              v-model="invoice.client.phone"
              type="text"
              class="form-control" />
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-12">
            <label class="form-label">Address</label>
            <textarea
              v-model="invoice.client.address"
              class="form-control"></textarea>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-6">
            <label class="form-label">Invoice Type</label>
            <select v-model="invoice.invoice_type" class="form-select">
              <option value="sales">Sales</option>
              <option value="maintenance">maintenance</option>
            </select>
          </div>
        </div>
        <div v-if="clientMode === 'add'" class="row mt-2">
          <div class="col-md-12 text-end">
            <button class="btn btn-success" @click="addClient">
              Save Client
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Discount & Tax</div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <label class="form-label">Discount Type</label>
            <select
              v-model="invoice.discount_type"
              class="form-select"
              @change="calculateTotal">
              <option value="">None</option>
              <option value="flat_rate">Flat Rate</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Discount Value</label>
            <input
              v-model.number="invoice.discount_value"
              type="number"
              class="form-control"
              step="0.01"
              @input="calculateTotal" />
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-6">
            <label class="form-label">Tax (%)</label>
            <input
              v-model.number="invoice.tax"
              type="number"
              class="form-control"
              step="0.01"
              @input="calculateTotal" />
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Invoice Items</div>
      <div class="card-body">
        <div
          v-for="(item, index) in invoice.items"
          :key="index"
          class="row mb-2">
          <div class="col-md-3">
            <label class="form-label">Item Name</label>
            <input
              v-model="item.name"
              type="text"
              class="form-control"
              required />
          </div>
          <div class="col-md-3">
            <label class="form-label">Description</label>
            <input
              v-model="item.description"
              type="text"
              class="form-control" />
          </div>
          <div class="col-md-2">
            <label class="form-label">Quantity</label>
            <input
              v-model.number="item.quantity"
              type="number"
              class="form-control"
              min="1"
              required
              @input="calculateTotal" />
          </div>
          <div class="col-md-2">
            <label class="form-label">Price</label>
            <input
              v-model.number="item.price"
              type="number"
              class="form-control"
              step="0.01"
              required
              @input="calculateTotal" />
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-danger" @click="removeItem(index)">
              Remove
            </button>
          </div>
        </div>
        <button class="btn btn-success" @click="addItem">+ Add Item</button>
      </div>
    </div>

    <div class="card mb-3">
      <div class="card-header">Calculation</div>
      <div class="card-body">
        <p><strong>Subtotal:</strong> {{ subtotal.toFixed(2) }}</p>
        <p><strong>Discount:</strong> {{ discountAmount.toFixed(2) }}</p>
        <p><strong>Tax:</strong> {{ taxAmount.toFixed(2) }}</p>
        <p class="fs-5">
          <strong>Final Total:</strong> {{ invoice.final_total.toFixed(2) }}
        </p>
      </div>
    </div>

    <div class="text-end">
      <button @click="createInvoice" class="btn btn-primary">
        Save Invoice
      </button>
      <RouterLink to="/" class="btn btn-secondary ms-2">Cancel</RouterLink>
    </div>
  </div>
</template>

<script>
import { ref, computed, watch, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";

export default {
  setup() {
    const route = useRoute();
    const router = useRouter();
    const invoiceType = ref(route.params.type || "sales");
    const invoice = ref({
      date: "",
      representative: "",
      client: {
        id: "",
        name: "",
        type: "business",
        email: "",
        phone: "",
        address: "",
      },
      client_id: "",
      invoice_type: "sales",
      discount: "",
      discount_value: 0,
      tax: 0,
      final_total: 0,
      items: [],
    });
    const searchQuery = ref("");
    const searchResults = ref([]);
    const showAddClientForm = ref(false);
    const showDropdown = ref(false);
    const allClients = ref([]);
    const clientMode = ref("search");
    // Fetch clients based on search query
    const loadAllClients = async () => {
      try {
        const response = await fetch("http://127.0.0.1:8000/api/clients");
        if (response.ok) {
          allClients.value = await response.json();
          searchResults.value = allClients.value;
        } else {
          console.error("Failed to load clients.");
        }
      } catch (error) {
        console.error("Error fetching clients:", error);
      }
    };
    // Filter clients based on search query
    watch(searchQuery, (newQuery) => {
      if (newQuery.length < 2) {
        searchResults.value = allClients.value;
      } else {
        searchResults.value = allClients.value.filter((client) =>
          client.name.toLowerCase().includes(newQuery.toLowerCase())
        );
      }
      showDropdown.value = true;
    });

    // Call loadAllClients when the component is mounted
    onMounted(() => {
      loadAllClients();
    });

    const selectClient = (client) => {
      invoice.value.client = { ...client };
      searchQuery.value = client.name;
      searchResults.value = [];
      showDropdown.value = false;
    };

    const addClient = async () => {
      const { id, ...clientWithoutId } = invoice.value.client;
      console.log(clientWithoutId);
      try {
        const response = await fetch("http://127.0.0.1:8000/api/clients", {
          method: "POST",
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
          },
          body: JSON.stringify(clientWithoutId),
        });

        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const addedClient = await response.json();
        clientMode.value = "search";
        invoice.value.client_id = addedClient.id;
        allClients.value.push(addedClient);
        selectClient(addedClient);
      } catch (error) {
        console.error("Error adding client:", error);
      }
    };
    const showAddClient = () => {
      showAddClientForm.value = true;
      showDropdown.value = false;
    };

    watch(searchQuery, () => {
      showAddClientForm.value = false;
    });

    const subtotal = computed(() =>
      invoice.value.items.reduce(
        (sum, item) => sum + item.quantity * item.price,
        0
      )
    );

    const discountAmount = computed(() => {
      if (invoice.value.discount_type === "percentage") {
        return (subtotal.value * invoice.value.discount_value) / 100;
      } else if (invoice.value.discount_type === "flat_rate") {
        return invoice.value.discount_value;
      }
      return 0;
    });

    const taxAmount = computed(
      () => ((subtotal.value - discountAmount.value) * invoice.value.tax) / 100
    );

    const calculateTotal = () => {
      invoice.value.final_total =
        subtotal.value - discountAmount.value + taxAmount.value;
    };

    const addItem = () =>
      invoice.value.items.push({
        name: "",
        description: "",
        quantity: 1,
        price: 0,
      });
    const removeItem = (index) => invoice.value.items.splice(index, 1);

    const hideDropdown = () => {
      setTimeout(() => {
        showDropdown.value = false;
      }, 200);
    };
    const createInvoice = async () => {
      try {
        const invoiceData = {
          date: invoice.value.date,
          representative: invoice.value.representative,
          client_id: invoice.value.client.id,
          invoice_type: invoice.value.invoice_type,
          discount: invoice.value.discount,
          discount_value: invoice.value.discount_value,
          tax: invoice.value.tax,
          final_total: invoice.value.final_total,
          items: invoice.value.items,
        };

        const url = `http://127.0.0.1:8000/api/invoices/${invoiceType.value}`;

        // Send the invoice data to the API using fetch
        const response = await fetch(url, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
          body: JSON.stringify(invoiceData),
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || "Failed to create invoice.");
        }
        const data = await response.json();
        router.push("/");
        console.log(data);
      } catch (error) {
        console.error("Error creating invoice:", error.message);
        throw error;
      }
    };
    return {
      invoiceType,
      invoice,
      subtotal,
      discountAmount,
      taxAmount,
      addItem,
      removeItem,
      calculateTotal,
      searchQuery,
      searchResults,
      selectClient,
      showAddClientForm,
      addClient,
      showDropdown,
      showAddClient,
      hideDropdown,
      clientMode,
      createInvoice,
    };
  },
};
</script>
