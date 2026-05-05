

import { motion } from 'framer-motion';
import { User, Mail, Phone, Hash, BookOpen, Calendar, Edit, ShieldCheck } from 'lucide-react';
import { getCurrentUser } from '../lib/store';
import { useNavigate } from 'react-router-dom';

const InfoRow = ({ icon: Icon, label, value }: any) => (
  <div className="flex items-center gap-4 p-4 bg-white/50 dark:bg-gray-800/50 rounded-xl border border-white/20">
    <div className="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center text-blue-500">
      <Icon className="w-5 h-5" />
    </div>
    <div>
      <p className="text-xs text-gray-500 font-medium uppercase tracking-wider">{label}</p>
      <p className="text-gray-800 dark:text-white font-semibold">{value}</p>
    </div>
  </div>
);

const Dashboard = () => {
  const user = getCurrentUser();
  const navigate = useNavigate();

  if (!user) {
    navigate('/login');
    return null;
  }

  return (
    <div className="space-y-8">
      <div className="flex flex-col md:flex-row gap-8">
        {/* Profile Card */}
        <motion.div 
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          className="glass-card flex-shrink-0 w-full md:w-80 p-8 rounded-3xl text-center flex flex-col items-center"
        >
          <div className="relative">
            <img 
              src={user.profileImage} 
              alt={user.fullName} 
              className="w-32 h-32 rounded-3xl object-cover border-4 border-blue-500 shadow-xl"
            />
            <div className="absolute -bottom-2 -right-2 bg-green-500 text-white p-1.5 rounded-full border-4 border-white dark:border-gray-900">
              <ShieldCheck className="w-4 h-4" />
            </div>
          </div>
          
          <h2 className="mt-6 text-2xl font-bold text-gray-800 dark:text-white">{user.fullName}</h2>
          <p className="text-blue-500 font-medium">@{user.username}</p>
          
          <div className="mt-8 w-full space-y-3">
            <button className="w-full py-3 bg-blue-600 text-white font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-blue-700 transition-all">
              <Edit className="w-4 h-4" />
              Edit Profile
            </button>
            <button className="w-full py-3 glass text-gray-700 dark:text-white font-bold rounded-xl hover:bg-white/10 transition-all border border-gray-300 dark:border-gray-600">
              Change Password
            </button>
          </div>
        </motion.div>

        {/* Info Grid */}
        <div className="flex-1 space-y-6">
          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            className="glass-card p-8 rounded-3xl"
          >
            <div className="flex justify-between items-center mb-6">
              <h3 className="text-xl font-bold text-gray-800 dark:text-white">Student Information</h3>
              <span className="px-3 py-1 bg-green-500/10 text-green-500 text-xs font-bold rounded-full border border-green-500/20">Active Student</span>
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <InfoRow icon={Mail} label="Email Address" value={user.email} />
              <InfoRow icon={Phone} label="Phone Number" value={user.phone} />
              <InfoRow icon={Hash} label="Student ID" value={user.studentId} />
              <InfoRow icon={BookOpen} label="Department" value={user.department} />
              <InfoRow icon={Calendar} label="Member Since" value={new Date(user.createdAt).toLocaleDateString()} />
              <InfoRow icon={User} label="Username" value={user.username} />
            </div>
          </motion.div>

          <motion.div 
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.1 }}
            className="grid grid-cols-1 sm:grid-cols-3 gap-4"
          >
            <div className="glass-card p-6 rounded-2xl text-center">
              <p className="text-3xl font-bold text-blue-500">3.8</p>
              <p className="text-sm text-gray-500 font-medium">Current GPA</p>
            </div>
            <div className="glass-card p-6 rounded-2xl text-center">
              <p className="text-3xl font-bold text-purple-500">12</p>
              <p className="text-sm text-gray-500 font-medium">Courses Enrolled</p>
            </div>
            <div className="glass-card p-6 rounded-2xl text-center">
              <p className="text-3xl font-bold text-pink-500">92%</p>
              <p className="text-sm text-gray-500 font-medium">Attendance</p>
            </div>
          </motion.div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
