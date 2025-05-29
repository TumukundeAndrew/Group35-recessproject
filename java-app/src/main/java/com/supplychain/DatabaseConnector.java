package com.supplychain;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;

public class DatabaseConnector {
    private static final String URL = "jdbc:mysql://localhost:3306/supply_chain?useSSL=false";
    private static final String USER = "root";
    private static final String PASSWORD = "";

    public static void saveValidationResult(long applicationId, int financialScore, int reputationScore, boolean complianceStatus) throws Exception {
        try (Connection conn = DriverManager.getConnection(URL, USER, PASSWORD)) {
            String sql = "INSERT INTO vendor_validation_checks (vendor_application_id, financial_score, reputation_score, compliance_status) VALUES (?, ?, ?, ?)";
            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setLong(1, applicationId);
            stmt.setInt(2, financialScore);
            stmt.setInt(3, reputationScore);
            stmt.setString(4, complianceStatus ? "compliant" : "non-compliant");
            stmt.executeUpdate();
        }
    }
}