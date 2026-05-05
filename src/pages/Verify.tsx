
import { useState, useEffect } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { motion } from 'framer-motion';
import { ShieldCheck, RefreshCw } from 'lucide-react';
import Swal from 'sweetalert2';
import { getUsers, saveUsers } from '../lib/store';

const Verify = () => {
  const [otp, setOtp] = useState(['', '', '', '', '', '']);
  const [timeLeft, setTimeLeft] = useState(60);
  const location = useLocation();
  const navigate = useNavigate();
  const email = location.state?.email;

  useEffect(() => {
    if (!email) {
      navigate('/login');
      return;
    }

    if (timeLeft > 0) {
      const timer = setTimeout(() => setTimeLeft(timeLeft - 1), 1000);
      return () => clearTimeout(timer);
    }
  }, [timeLeft, email, navigate]);

  const handleChange = (element: HTMLInputElement, index: number) => {
    if (isNaN(Number(element.value))) return false;

    const newOtp = [...otp];
    newOtp[index] = element.value;
    setOtp(newOtp);

    // Focus next input
    if (element.nextSibling && element.value !== '') {
      (element.nextSibling as HTMLInputElement).focus();
    }
  };

  const handleVerify = () => {
    const enteredOtp = otp.join('');
    const users = getUsers();
    const userIndex = users.findIndex((u: any) => u.email === email);

    if (userIndex !== -1 && users[userIndex].verificationOTP === enteredOtp) {
      users[userIndex].emailVerified = true;
      delete users[userIndex].verificationOTP;
      saveUsers(users);
      
      Swal.fire('Verified!', 'Your email has been successfully verified.', 'success').then(() => {
        navigate('/login');
      });
    } else {
      Swal.fire('Error', 'Invalid OTP code. Please try again.', 'error');
    }
  };

  const handleResend = () => {
    setTimeLeft(60);
    Swal.fire('Sent!', 'A new OTP has been sent to your email.', 'success');
  };

  return (
    <div className="flex justify-center items-center py-20">
      <motion.div 
        initial={{ opacity: 0, scale: 0.9 }}
        animate={{ opacity: 1, scale: 1 }}
        className="glass-card w-full max-w-md p-8 rounded-3xl text-center"
      >
        <div className="w-16 h-16 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 text-green-500">
          <ShieldCheck className="w-8 h-8" />
        </div>
        <h2 className="text-2xl font-bold text-gray-800 dark:text-white mb-2">Verify Your Email</h2>
        <p className="text-gray-600 dark:text-gray-400 mb-8">
          We've sent a 6-digit code to <br />
          <span className="font-semibold text-gray-800 dark:text-white">{email}</span>
        </p>

        <div className="flex justify-center gap-2 mb-8">
          {otp.map((data, index) => (
            <input
              key={index}
              type="text"
              maxLength={1}
              value={data}
              onChange={e => handleChange(e.target, index)}
              className="w-12 h-14 text-center text-xl font-bold rounded-xl border border-gray-300 dark:border-gray-600 bg-white/50 dark:bg-gray-800/50 focus:ring-2 focus:ring-blue-500 outline-none"
            />
          ))}
        </div>

        <button 
          onClick={handleVerify}
          className="w-full py-4 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-all mb-6"
        >
          Verify Account
        </button>

        <div className="text-sm">
          {timeLeft > 0 ? (
            <p className="text-gray-500">Resend code in <span className="font-bold">{timeLeft}s</span></p>
          ) : (
            <button onClick={handleResend} className="flex items-center gap-2 mx-auto text-blue-500 font-semibold hover:underline">
              <RefreshCw className="w-4 h-4" />
              Resend Code
            </button>
          )}
        </div>
      </motion.div>
    </div>
  );
};

export default Verify;
