import ReactDOM from 'react-dom/client';
import { motion } from 'framer-motion';

const AnimatedBackground = () => {
    return (
        <div className="fixed top-0 left-0 w-full h-full -z-10 overflow-hidden bg-white dark:bg-gray-900">
            <motion.div
                className="absolute top-[20%] left-[10%] w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 dark:opacity-30"
                animate={{
                    scale: [1, 1.2, 1],
                    x: [0, 50, 0],
                    y: [0, -50, 0],
                }}
                transition={{
                    duration: 15,
                    repeat: Infinity,
                    repeatType: 'reverse',
                    ease: 'easeInOut',
                }}
            />
            <motion.div
                className="absolute bottom-[20%] right-[10%] w-72 h-72 bg-yellow-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 dark:opacity-30"
                animate={{
                    scale: [1, 1.2, 1],
                    x: [0, -50, 0],
                    y: [0, 50, 0],
                }}
                transition={{
                    duration: 20,
                    repeat: Infinity,
                    repeatType: 'reverse',
                    ease: 'easeInOut',
                    delay: 5,
                }}
            />
             <motion.div
                className="absolute top-[5%] right-[25%] w-56 h-56 bg-blue-300 rounded-full mix-blend-multiply filter blur-xl opacity-60 dark:opacity-20"
                animate={{
                    scale: [1, 1.1, 1],
                    x: [0, -30, 0],
                    y: [0, 40, 0],
                }}
                transition={{
                    duration: 25,
                    repeat: Infinity,
                    repeatType: 'reverse',
                    ease: 'easeInOut',
                    delay: 10,
                }}
            />
        </div>
    );
};

const container = document.getElementById('animated-bg-container');
if (container) {
    const root = ReactDOM.createRoot(container);
    root.render(
        <React.StrictMode>
            <AnimatedBackground />
        </React.StrictMode>
    );
}
