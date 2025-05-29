package com.supplychain;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.PreparedStatement;
import java.time.LocalDate;

public class VisitScheduler {
    private static final String URL = "jdbc:mysql://localhost:3306/supply_chain?useSSL=false";
    private static final String USER = "root";
    private static final String PASSWORD = "";

    public static void scheduleVisit(long applicationId) throws Exception {
        try (Connection conn = DriverManager.getConnection(URL, USER, PASSWORD)) {
            String sql = "INSERT INTO scheduled_visits (vendor_application_id, visit_date, status) VALUES (?, ?, ?)";
            PreparedStatement stmt = conn.prepareStatement(sql);
            stmt.setLong(1, applicationId);
            stmt.setDate(2, java.sql.Date.valueOf(LocalDate.now().plusDays(7)));
            stmt.setString(3, "scheduled");
            stmt.executeUpdate();
        }
    }
}