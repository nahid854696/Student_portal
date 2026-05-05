

import { motion } from 'framer-motion';
import { Shield, GraduationCap, Zap, Lock } from 'lucide-react';
import { Link } from 'react-router-dom';

const FeatureCard = ({ icon: Icon, title, description }: any) => (
  <motion.div 
    whileHover={{ scale: 1.05 }}
    className="glass-card p-6 rounded-2xl text-center"
  >
    <div className="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-4 text-blue-400">
      <Icon className="w-6 h-6" />
    </div>
    <h3 className="text-xl font-semibold mb-2 text-gray-800 dark:text-white">{title}</h3>
    <p className="text-gray-600 dark:text-gray-400">{description}</p>
  </motion.div>
);

const Home = () => {
  return (
    <div className="flex flex-col items-center justify-center min-h-[80vh] text-center">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8 }}
      >
        <h1 className="text-4xl md:text-6xl font-bold mb-6 tracking-tight text-white">
          Student Authentication <br />
          <span className="text-blue-500">Portal</span>
        </h1>
        <p className="text-lg text-gray-400 mb-10 max-w-xl mx-auto">
          A secure, minimalist management system for modern educational institutions.
        </p>
        
        <div className="flex flex-wrap gap-4 justify-center mb-20">
          <Link to="/register" className="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-all">
            Get Started
          </Link>
          <Link to="/login" className="px-8 py-3 bg-gray-800 text-white font-semibold rounded-lg border border-gray-700 hover:bg-gray-700 transition-all">
            Login
          </Link>
        </div>
      </motion.div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 w-full max-w-6xl">
        <FeatureCard 
          icon={Shield} 
          title="Secure Auth" 
          description="Advanced encryption and multi-factor verification system."
        />
        <FeatureCard 
          icon={Zap} 
          title="Fast Performance" 
          description="Optimized for speed with modern frontend techniques."
        />
        <FeatureCard 
          icon={GraduationCap} 
          title="Academic Ready" 
          description="Tailored specifically for student and faculty needs."
        />
        <FeatureCard 
          icon={Lock} 
          title="Privacy First" 
          description="Your data is encrypted and strictly protected at all times."
        />
      </div>
    </div>
  );
};

export default Home;
