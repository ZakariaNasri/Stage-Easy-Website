<template>
    <v-dialog v-model="dialog" scrollable max-width="500px" transition="dialog-transition">
      <AlertCreateAccount
        header="Attestation"
        :msg="msg2"
        imgSrc="require('@/assets/icons/bell.png')"
        v-if="showAlertCreateModel"
        @close="showAlertCreateModel = false"
        @click:outside="showAlertCreateModel = false"
      />
  
      <v-alert :value="alert1" class="mb-0" transition="scale-transition">
        <v-row class="d-flex justify-center">
          <img src="@/assets/icons/Checkmark Icon.I01.2k.png" style="width: 140px; height: 140px;">
        </v-row>
        <h1 class="header text-center">{{ header }}</h1>
        <h3 class="paragraph text-center">{{ msg }}</h3>
        <v-btn v-if="finalNote>=10" class="mt-2" @click="attesReady" :block="true" color="#63D693">
          Preparer l'attestation
        </v-btn>
      </v-alert>
    </v-dialog>
  </template>
  
  <script>
  import api from "@/api";
import AlertCreateAccount from "./alertCreateAccount.vue";
  
  export default {
    components: { AlertCreateAccount },
    data() {
        return {
            dialog: true,
            showAlertCreateModel: false,
            msg2: ""
        };
    },
    props: ["header", "msg", "id_Etud","id_Stage","finalNote"],
    methods: {
        async attesReady() {
  try {
    const response = await api.post("/genererPdf", { id_Etud: this.id_Etud , id_Stage:this.id_Stage}, {responseType: 'blob'});
    const file = new Blob([response.data], { type: 'application/pdf' });
    const fileURL = URL.createObjectURL(file);
    
    const printWindow = window.open(fileURL);
    
    printWindow.onload = function() {
      printWindow.print();
      printWindow.onafterprint = function() {
        printWindow.close();
      };
    };
    
    this.msg2 = "L'attestation a été envoyée";
    this.showAlertCreateModel = !this.showAlertCreateModel;
  } catch (error) {
    console.log(error);
  }
},

  // showAttestBtn(){
  //   if (msg) {
      
  //   }
  // }

//         async attesReady() {
//   try {
//     const response = await api.post("/genererPdf", { id: this.id_Etud });
//     const fileURL = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
    
//     const printWindow = window.open(fileURL);
    
//     printWindow.onload = function() {
//       printWindow.print();
//       printWindow.onafterprint = function() {
//         printWindow.close();
//       };
//     };
    
//     this.msg2 = "L'attestation a été envoyée";
//     this.showAlertCreateModel = !this.showAlertCreateModel;
//   } catch (error) {
//     console.log(error);
//   }
// }

    },
    
}
  </script>
  
  <style scoped>
  .header {
    font-size: 30px;
    font-family: 'Outfit';
    color: #63D693;
  }
  .paragraph {
    font-size: 17px;
    font-family: 'Outfit';
    color: #120030;
  }
  </style>
  
  import axios from 'axios';

// Assuming you have a function to make the axios request and get the PDF data
async function downloadPdf(studentId) {
  try {
    const response = await axios.post('/your-api-endpoint', { studentId }, { responseType: 'blob' });

    // Create a Blob object from the response data
    const blob = new Blob([response.data], { type: 'application/pdf' });

    // Create a temporary URL for the Blob object
    const url = window.URL.createObjectURL(blob);

    // Create a link element to trigger the download
    const link = document.createElement('a');
    link.href = url;
    link.download = 'file.pdf';

    // Append the link to the document and trigger the download
    document.body.appendChild(link);
    link.click();

    // Clean up the temporary URL and link element
    window.URL.revokeObjectURL(url);
    document.body.removeChild(link);
  } catch (error) {
    // Handle error
    console.error('Failed to download PDF:', error);
  }
}