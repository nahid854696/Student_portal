
import { motion } from 'framer-motion';
import { Shield } from 'lucide-react';

const Terms = () => {
  return (
    <div className="flex justify-center items-center py-20">
      <motion.div 
        initial={{ opacity: 0 }}
        animate={{ opacity: 1 }}
        className="glass-card w-full max-w-3xl p-8 rounded-3xl"
      >
        <div className="flex items-center gap-4 mb-8">
          <Shield className="w-8 h-8 text-blue-500" />
          <h1 className="text-3xl font-bold">Terms & Conditions</h1>
        </div>
        <div className="space-y-4 text-gray-700 dark:text-gray-300">
          <p>Welcome to EduPortal. By using our services, you agree to the following terms:</p>
          <h3 className="text-xl font-bold">1. Data Privacy</h3>
          <p>We take your privacy seriously. Your student data is encrypted and used only for educational purposes.</p>
          <h3 className="text-xl font-bold">2. Account Security</h3>
          <p>You are responsible for maintaining the confidentiality of your password and account details.</p>
          <h3 className="text-xl font-bold">3. Acceptable Use</h3>
          <p>This portal is for authorized student use only. Any attempt to breach security will result in account suspension.</p>
        </div>
      </motion.div>
    </div>
  );
};

export default Terms;
