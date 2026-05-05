
import { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { motion } from 'framer-motion';
import { Mail, Lock, Eye, EyeOff, LogIn, ShieldAlert } from 'lucide-react';
import { Link, useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';
import { getUsers, setCurrentUser } from '../lib/store';

const loginSchema = z.object({
  identifier: z.string().min(3, 'Email or username is required'),
  password: z.string().min(1, 'Password is required'),
  rememberMe: z.boolean().optional()
});

type LoginForm = z.infer<typeof loginSchema>;

const Login = () => {
  const [showPassword, setShowPassword] = useState(false);
  const [loginAttempts, setLoginAttempts] = useState(0);
  const navigate = useNavigate();

  const { register, handleSubmit, formState: { errors, isSubmitting } } = useForm<LoginForm>({
    resolver: zodResolver(loginSchema)
  });

  const onSubmit = async (data: LoginForm) => {
    // Brute force protection simulation
    if (loginAttempts >= 5) {
      Swal.fire('Account Locked', 'Too many failed attempts. Please try again later or contact support.', 'error');
      return;
    }

    const users = getUsers();
    const user = users.find((u: any) => 
      (u.email === data.identifier || u.username === data.identifier) && 
      u.password === data.password
    );

    if (user) {
      if (!user.emailVerified) {
        Swal.fire({
          title: 'Email Not Verified',
          text: 'Please verify your email before logging in.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Verify Now'
        }).then((result) => {
          if (result.isConfirmed) {
            navigate('/verify', { state: { email: user.email } });
          }
        });
        return;
      }

      setCurrentUser(user);
      Swal.fire({
        title: 'Welcome Back!',
        text: `Logged in as ${user.fullName}`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        navigate('/dashboard');
      });
    } else {
      setLoginAttempts(prev => prev + 1);
      Swal.fire('Invalid Credentials', 'Please check your email/username and password.', 'error');
    }
  };

  return (
    <div className="flex justify-center items-center py-20">
      <motion.div 
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="glass-card w-full max-w-md p-8 rounded-3xl"
      >
        <div className="text-center mb-8">
          <div className="w-16 h-16 bg-blue-600/20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-blue-500">
            <LogIn className="w-8 h-8" />
          </div>
          <h2 className="text-3xl font-bold text-gray-800 dark:text-white mb-2">Student Login</h2>
          <p className="text-gray-600 dark:text-gray-400">Welcome back! Please enter your details.</p>
        </div>

        {loginAttempts >= 3 && (
          <div className="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-xl flex items-center gap-3 text-red-500 text-sm">
            <ShieldAlert className="w-5 h-5" />
            <span>Multiple failed attempts. CAPTCHA required on next attempt.</span>
          </div>
        )}

        <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
          <div className="space-y-2">
            <label className="text-sm font-medium text-gray-700 dark:text-gray-300">Email or Username</label>
            <div className="relative">
              <Mail className="absolute left-3 top-3 w-5 h-5 text-gray-400" />
              <input 
                {...register('identifier')} 
                className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                placeholder="john@example.com" 
              />
            </div>
            {errors.identifier && <p className="text-xs text-red-500 mt-1">{errors.identifier.message}</p>}
          </div>

          <div className="space-y-2">
            <div className="flex justify-between items-center">
              <label className="text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
              <Link to="/forgot-password" className="text-xs text-blue-500 hover:underline">Forgot Password?</Link>
            </div>
            <div className="relative">
              <Lock className="absolute left-3 top-3 w-5 h-5 text-gray-400" />
              <input 
                type={showPassword ? 'text' : 'password'} 
                {...register('password')} 
                className="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 focus:ring-2 focus:ring-blue-500 outline-none transition-all" 
                placeholder="••••••••" 
              />
              <button type="button" onClick={() => setShowPassword(!showPassword)} className="absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
              </button>
            </div>
            {errors.password && <p className="text-xs text-red-500 mt-1">{errors.password.message}</p>}
          </div>

          <div className="flex items-center gap-3">
            <input type="checkbox" {...register('rememberMe')} className="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-gray-300" />
            <label className="text-sm text-gray-600 dark:text-gray-400">Remember me for 30 days</label>
          </div>

          <button 
            type="submit" 
            disabled={isSubmitting}
            className="w-full py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-blue-500/25 transition-all transform hover:-translate-y-0.5 active:scale-95 disabled:opacity-70 flex items-center justify-center gap-2"
          >
            {isSubmitting ? (
              <div className="w-6 h-6 border-2 border-white/30 border-t-white rounded-full animate-spin" />
            ) : (
              <>
                <LogIn className="w-5 h-5" />
                <span>Sign In</span>
              </>
            )}
          </button>

          <p className="text-center text-gray-600 dark:text-gray-400">
            Don't have an account? <Link to="/register" className="text-blue-500 font-semibold hover:underline">Create Account</Link>
          </p>
        </form>
      </motion.div>
    </div>
  );
};

export default Login;
