import { useState } from "react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import { UserPlus } from "lucide-react";

export default function AdminSignup() {
  const [username, setUsername] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [error, setError] = useState("");

  const handleSignup = () => {
    if (password !== confirmPassword) {
      setError("Passwords do not match.");
      return;
    }
    alert("Signup successful! Proceed to login.");
    // Implement backend signup logic here
  };

  return (
    <div className="relative flex h-screen w-full items-center justify-center bg-gradient-to-r from-green-500 to-blue-600">
      <motion.div
        initial={{ opacity: 0, scale: 0.9 }}
        animate={{ opacity: 1, scale: 1 }}
        transition={{ duration: 0.5 }}
        className="w-full max-w-md"
      >
        <Card className="shadow-xl rounded-2xl bg-white/90">
          <CardHeader className="text-center">
            <CardTitle className="text-2xl font-bold text-gray-700 flex items-center justify-center gap-2">
              <UserPlus className="w-6 h-6" /> Admin Signup
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
              type="email"
              placeholder="Email"
              className="mb-3"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
            <Input
              type="tel"
              placeholder="Phone Number"
              className="mb-3"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
            />
            <Input
              type="password"
              placeholder="Password"
              className="mb-3"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
            />
            <Input
              type="password"
              placeholder="Confirm Password"
              className="mb-3"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
            />
            {error && <p className="text-red-500 text-sm">{error}</p>}
            <Button className="w-full mt-4" onClick={handleSignup}>Sign Up</Button>
          </CardContent>
        </Card>
      </motion.div>
    </div>
  );
}
