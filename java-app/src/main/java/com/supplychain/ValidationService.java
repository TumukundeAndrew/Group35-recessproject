package com.supplychain;

public class ValidationService {
    public static boolean validateVendor(String pdfData) {
        int financialScore = calculateFinancialScore(pdfData);
        int reputationScore = calculateReputationScore(pdfData);
        boolean complianceStatus = checkCompliance(pdfData);
        return financialScore > 70 && reputationScore > 60 && complianceStatus;
    }

    private static int calculateFinancialScore(String data) {
        return data.contains("revenue") || data.contains("profit") ? 80 : 50;
    }

    private static int calculateReputationScore(String data) {
        return data.contains("awards") || data.contains("reviews") ? 75 : 55;
    }

    private static boolean checkCompliance(String data) {
        return data.contains("certification") || data.contains("license");
    }
}