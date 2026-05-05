
export const getUsers = () => {
  const users = localStorage.getItem('students_db');
  return users ? JSON.parse(users) : [];
};

export const saveUsers = (users: any[]) => {
  localStorage.setItem('students_db', JSON.stringify(users));
};

export const getCurrentUser = () => {
  const user = sessionStorage.getItem('current_student');
  return user ? JSON.parse(user) : null;
};

export const setCurrentUser = (user: any) => {
  sessionStorage.setItem('current_student', JSON.stringify(user));
};

export const logoutUser = () => {
  sessionStorage.removeItem('current_student');
};

export const generateOTP = () => {
  return Math.floor(100000 + Math.random() * 900000).toString();
};
