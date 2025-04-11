import { useState, useEffect } from "react";
import { BarChart2, Users, Settings, FileText, Map, Package, Bell, Tool, Database } from "lucide-react";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from "recharts";

const Sidebar = ({ setSection }) => (
  <aside className="w-64 bg-gray-900 text-white h-screen p-5">
    <h1 className="text-xl font-bold mb-6">Admin Dashboard</h1>
    <nav>
      <button onClick={() => setSection("overview")} className="flex items-center p-3 hover:bg-gray-700 w-full rounded">
        <BarChart2 className="mr-2" /> Overview
      </button>
      <button onClick={() => setSection("users")} className="flex items-center p-3 hover:bg-gray-700 w-full rounded">
        <Users className="mr-2" /> Users
      </button>
      <button onClick={() => setSection("content")} className="flex items-center p-3 hover:bg-gray-700 w-full rounded">
        <FileText className="mr-2" /> Content
      </button>
      <button onClick={() => setSection("map")} className="flex items-center p-3 hover:bg-gray-700 w-full rounded">
        <Map className="mr-2" /> Map
      </button>
      <button onClick={() => setSection("settings")} className="flex items-center p-3 hover:bg-gray-700 w-full rounded">
        <Settings className="mr-2" /> Settings
      </button>
    </nav>
  </aside>
);

const SettingsSection = () => {
  const [activeSetting, setActiveSetting] = useState(null);

  const settingsOptions = [
    { name: "General Settings", description: "Update website title, description, and logo." },
    { name: "User Management", description: "Manage roles, permissions, and user access." },
    { name: "Security", description: "Enable 2FA, manage login settings, and configure API security." },
    { name: "Asset Management", description: "View and manage digital and physical assets." },
    { name: "Notification Settings", description: "Customize email and push notifications." },
    { name: "Data Backup & Restore", description: "Manage database backups and restore options." }
  ];

  return (
    <div>
      <h2 className="text-xl font-bold mb-4">Settings</h2>
      <div className="grid grid-cols-2 gap-4">
        {settingsOptions.map((setting, index) => (
          <button key={index} onClick={() => setActiveSetting(setting.name)} className="p-4 bg-white shadow rounded hover:bg-gray-200">
            <h3 className="text-lg font-semibold">{setting.name}</h3>
            <p>{setting.description}</p>
          </button>
        ))}
      </div>
      {activeSetting && (
        <div className="mt-6 p-4 bg-gray-200 rounded">
          <h3 className="text-lg font-semibold">{activeSetting}</h3>
          <p>Settings options for {activeSetting} will be displayed here.</p>
        </div>
      )}
    </div>
  );
};

const OverviewSection = () => {
  const [totalUsers, setTotalUsers] = useState(0);
  const [newSignups, setNewSignups] = useState(0);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch("/api/dashboard-metrics");
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        const data = await response.json();
        setTotalUsers(data.totalUsers || 0);
        setNewSignups(data.newSignups || 0);
      } catch (error) {
        console.error("Error fetching dashboard metrics:", error);
      }
    };

    fetchData();
    const interval = setInterval(fetchData, 10000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div>
      <h2 className="text-xl font-bold mb-4">Dashboard Overview</h2>
      <div className="grid grid-cols-2 gap-4 mb-6">
        <Card className="p-4 bg-white shadow">
          <h3 className="text-lg font-semibold">Total Users</h3>
          <p className="text-2xl font-bold">{totalUsers}</p>
        </Card>
        <Card className="p-4 bg-white shadow">
          <h3 className="text-lg font-semibold">New Signups</h3>
          <p className="text-2xl font-bold">{newSignups}</p>
        </Card>
      </div>
    </div>
  );
};

export default function AdminDashboard() {
  const [section, setSection] = useState("overview");

  return (
    <div className="flex h-screen bg-gray-100">
      <Sidebar setSection={setSection} />
      <main className="flex-1 p-6 overflow-auto">
        <div className="flex justify-between items-center mb-4">
          <h1 className="text-2xl font-bold">Admin Panel</h1>
          <Button variant="outline">
            <Bell className="mr-2" /> Notifications
          </Button>
        </div>
        {section === "overview" && <OverviewSection />}
        {section === "users" && <div>Users Section</div>}
        {section === "content" && <div>Content Management</div>}
        {section === "map" && <div>Map View</div>}
        {section === "settings" && <SettingsSection />}
      </main>
    </div>
  );
}
