import { useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import { Lock } from "lucide-react";

export default function AdminLogin() {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const handleLogin = () => {
    if (username === "admin" && password === "password123") {
      alert("Login successful!");
      // Redirect to admin dashboard here
    } else {
      setError("Invalid credentials. Try again.");
    }
  };

  return (
    <div className="relative flex h-screen w-full items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
      <motion.div
        initial={{ opacity: 0, scale: 0.9 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.5 }}
        className="w-full max-w-md"
      >
        <Card className="shadow-xl rounded-2xl bg-white/90">
          <CardHeader className="text-center">
            <CardTitle className="text-2xl font-bold text-gray-700 flex items-center justify-center gap-2">
              <Lock className="w-6 h-6" /> Admin Login
            </CardTitle>
          </CardHeader>
          <CardContent>
            <Input
              placeholder="Username"
              className="mb-3"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
            />
            <Input
              type="password"
              placeholder="Password"
              className="mb-3"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
            {error && <p className="text-red-500 text-sm">{error}</p>}
            <Button className="w-full mt-4" onClick={handleLogin}>Login</Button>
          </CardContent>
        </Card>
      </motion.div>
    </div>
  );
}
