import { useState } from "react";
import { BarChart2, Users, Settings, FileText, Map, Package, Bell } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
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

const data = [
  { name: "Jan", users: 400 },
  { name: "Feb", users: 300 },
  { name: "Mar", users: 500 },
  { name: "Apr", users: 700 },
];

const Overview = () => (
  <div className="p-6">
    <h2 className="text-xl font-bold mb-4">Dashboard Overview</h2>
    <div className="grid grid-cols-3 gap-4">
      <Card>
        <CardContent className="p-4">
          <h3 className="text-lg font-semibold">Total Users</h3>
          <p className="text-2xl">1,250</p>
        </CardContent>
      </Card>
      <Card>
        <CardContent className="p-4">
          <h3 className="text-lg font-semibold">Map</h3>
          <p className="text-2xl">View</p>
        </CardContent>
      </Card>
      <Card>
        <CardContent className="p-4">
          <h3 className="text-lg font-semibold">Assets</h3>
          <p className="text-2xl">Manage</p>
        </CardContent>
      </Card>
    </div>
    <div className="mt-6">
      <h3 className="text-lg font-semibold">User Growth</h3>
      <ResponsiveContainer width="100%" height={300}>
        <LineChart data={data}>
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="name" />
          <YAxis />
          <Tooltip />
          <Line type="monotone" dataKey="users" stroke="#4F46E5" strokeWidth={2} />
        </LineChart>
      </ResponsiveContainer>
    </div>
  </div>
);

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
        {section === "overview" && <Overview />}
        {section === "users" && <div>Users Section</div>}
        {section === "content" && <div>Content Management</div>}
        {section === "map" && <div>Map View</div>}
        {section === "settings" && (
          <div>
            <h2 className="text-xl font-bold">Settings</h2>
            <button onClick={() => setSection("assets")} className="mt-4 p-2 bg-blue-500 text-white rounded">
              Asset Management
            </button>
          </div>
        )}
        {section === "assets" && <div>Asset Management Section</div>}
      </main>
    </div>
  );
}
