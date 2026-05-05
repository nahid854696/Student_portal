
import { useState } from 'react';
import { motion } from 'framer-motion';
import { Mail, ArrowLeft, Send } from 'lucide-react';
import { Link } from 'react-router-dom';
import Swal from 'sweetalert2';
import { getUsers } from '../lib/store';

const ForgotPassword = () => {
  const [email, setEmail] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    
    // Simulate API call
    setTimeout(() => {
      const users = getUsers();
      const user = users.find((u: any) => u.email === email);
      
      setIsLoading(false);
      
      if (user) {
        // In the PHP version, this calls mailer.php
        Swal.fire({
          title: 'Reset Link Sent',
          text: 'A password reset link has been sent to your email address. (In local dev, check the console for the mock link)',
          icon: 'success',
          confirmButtonColor: '#3b82f6'
        });
        console.log("Mock Reset Link: http://localhost/reset-password?token=mock_token_123");
      } else {
        Swal.fire('Error', 'No account found with that email address.', 'error');
      }
    }, 1500);
  };

  return (
    <div className="flex justify-center items-center py-20">
      <motion.div 
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="glass-card w-full max-w-md p-8 rounded-3xl"
      >
        <Link to="/login" className="flex items-center gap-2 text-sm text-gray-500 hover:text-blue-500 transition-colors mb-6">
          <ArrowLeft className="w-4 h-4" />
          Back to Login
        </Link>

        <div className="text-center mb-8">
          <div className="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-500">
            <Mail className="w-8 h-8" />
          </div>
          <h2 className="text-3xl font-bold text-gray-800 dark:text-white mb-2">Forgot Password?</h2>
          <p className="text-gray-600 dark:text-gray-400">Enter your email and we'll send you a link to reset your password.</p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6">
          <div className="space-y-2">
            <label className="text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
            <div className="relative">
              <Mail className="absolute left-3 top-3 w-5 h-5 text-gray-400" />
              <input 
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                placeholder="john@example.com" 
              />
            </div>
          </div>

          <button 
            type="submit" 
            disabled={isLoading}
            className="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-70 flex items-center justify-center gap-2"
          >
            {isLoading ? (
              <div className="w-6 h-6 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <>
                <Send className="w-5 h-5" />
                <span>Send Reset Link</span>
              </>
            )}
          </button>
        </form>
      </motion.div>
    </div>
  );
};

export default ForgotPassword;
