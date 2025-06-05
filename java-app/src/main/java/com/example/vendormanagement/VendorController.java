package com.example.vendormanagement;

     import org.springframework.web.bind.annotation.GetMapping;
     import org.springframework.web.bind.annotation.RequestParam;
     import org.springframework.web.bind.annotation.RestController;
     //import com.example.vendormanagement.PdfProcessor;

     @RestController
     public class VendorController {
         @GetMapping("/api/vendors")
         public String getVendors() {
             return "Vendor validation endpoint is working!";
         }

         @GetMapping("/")
         public String home() {
             return "Welcome to the Vendor Validation API!";
         }

         @GetMapping("/api/process-pdf")
         public String processPdf(@RequestParam String pdfPath) {
             try {
                 String text = PdfProcessor.extractData(pdfPath);
                 return "Extracted Text: " + text;
             } catch (Exception e) {
                 return "Error processing PDF: " + e.getMessage();
             }
         }
     }