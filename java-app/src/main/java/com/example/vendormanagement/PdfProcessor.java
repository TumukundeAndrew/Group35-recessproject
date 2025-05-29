package com.example.vendormanagement;

     import org.apache.pdfbox.Loader;
     import org.apache.pdfbox.pdmodel.PDDocument;
     import org.apache.pdfbox.text.PDFTextStripper;
     import java.io.File;

     public class PdfProcessor {
         public static String extractData(String pdfPath) throws Exception {
             File file = new File(pdfPath);
             if (!file.exists()) {
                 throw new IllegalArgumentException("PDF file not found: " + pdfPath);
             }
             try (PDDocument document = Loader.loadPDF(file)) {
                 PDFTextStripper stripper = new PDFTextStripper();
                 return stripper.getText(document);
             }
         }
     }