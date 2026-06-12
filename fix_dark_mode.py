import os
import re

dir_path = r'd:/TRPL Muhammad Ridho Syaputra/SEMESTER 4/PBL/trifacore/resources/views'

def add_dark_classes(content):
    # For backgrounds
    content = re.sub(r'\bbg-white(?!\s+dark:bg)\b', 'bg-white dark:bg-gray-800', content)
    content = re.sub(r'\bbg-gray-50(?!\s+dark:bg)\b', 'bg-gray-50 dark:bg-gray-700/50', content)
    content = re.sub(r'\bbg-gray-100(?!\s+dark:bg)\b', 'bg-gray-100 dark:bg-gray-700', content)
    content = re.sub(r'\bborder-gray-200(?!\s+dark:border)\b', 'border-gray-200 dark:border-gray-700', content)
    content = re.sub(r'\bborder-gray-300(?!\s+dark:border)\b', 'border-gray-300 dark:border-gray-600', content)
    
    # For text
    content = re.sub(r'\btext-gray-900(?!\s+dark:text)\b', 'text-gray-900 dark:text-gray-100', content)
    content = re.sub(r'\btext-gray-800(?!\s+dark:text)\b', 'text-gray-800 dark:text-gray-200', content)
    content = re.sub(r'\btext-gray-700(?!\s+dark:text)\b', 'text-gray-700 dark:text-gray-300', content)
    content = re.sub(r'\btext-gray-600(?!\s+dark:text)\b', 'text-gray-600 dark:text-gray-400', content)
    content = re.sub(r'\btext-gray-500(?!\s+dark:text)\b', 'text-gray-500 dark:text-gray-400', content)
    content = re.sub(r'\btext-black(?!\s+dark:text)\b', 'text-black dark:text-white', content)
    
    # Specific colors for dashboard (emerald, red, blue, amber)
    content = re.sub(r'\bbg-emerald-50(?!\s+dark:bg)\b', 'bg-emerald-50 dark:bg-emerald-900/30', content)
    content = re.sub(r'\bborder-emerald-200(?!\s+dark:border)\b', 'border-emerald-200 dark:border-emerald-800', content)
    content = re.sub(r'\bbg-emerald-100(?!\s+dark:bg)\b', 'bg-emerald-100 dark:bg-emerald-900/50', content)
    content = re.sub(r'\btext-emerald-800(?!\s+dark:text)\b', 'text-emerald-800 dark:text-emerald-300', content)
    content = re.sub(r'\btext-emerald-700(?!\s+dark:text)\b', 'text-emerald-700 dark:text-emerald-400', content)
    content = re.sub(r'\btext-emerald-600(?!\s+dark:text)\b', 'text-emerald-600 dark:text-emerald-500', content)
    
    content = re.sub(r'\bbg-red-50(?!\s+dark:bg)\b', 'bg-red-50 dark:bg-red-900/30', content)
    content = re.sub(r'\bborder-red-200(?!\s+dark:border)\b', 'border-red-200 dark:border-red-800', content)
    content = re.sub(r'\bbg-red-100(?!\s+dark:bg)\b', 'bg-red-100 dark:bg-red-900/50', content)
    content = re.sub(r'\btext-red-800(?!\s+dark:text)\b', 'text-red-800 dark:text-red-300', content)
    content = re.sub(r'\btext-red-700(?!\s+dark:text)\b', 'text-red-700 dark:text-red-400', content)
    content = re.sub(r'\btext-red-600(?!\s+dark:text)\b', 'text-red-600 dark:text-red-500', content)
    
    content = re.sub(r'\bbg-blue-50(?!\s+dark:bg)\b', 'bg-blue-50 dark:bg-blue-900/30', content)
    content = re.sub(r'\bborder-blue-200(?!\s+dark:border)\b', 'border-blue-200 dark:border-blue-800', content)
    content = re.sub(r'\bbg-blue-100(?!\s+dark:bg)\b', 'bg-blue-100 dark:bg-blue-900/50', content)
    content = re.sub(r'\btext-blue-800(?!\s+dark:text)\b', 'text-blue-800 dark:text-blue-300', content)
    content = re.sub(r'\btext-blue-700(?!\s+dark:text)\b', 'text-blue-700 dark:text-blue-400', content)
    content = re.sub(r'\btext-blue-600(?!\s+dark:text)\b', 'text-blue-600 dark:text-blue-500', content)
    
    content = re.sub(r'\bbg-amber-50(?!\s+dark:bg)\b', 'bg-amber-50 dark:bg-amber-900/30', content)
    content = re.sub(r'\bborder-amber-200(?!\s+dark:border)\b', 'border-amber-200 dark:border-amber-800', content)
    content = re.sub(r'\bbg-amber-100(?!\s+dark:bg)\b', 'bg-amber-100 dark:bg-amber-900/50', content)
    content = re.sub(r'\btext-amber-800(?!\s+dark:text)\b', 'text-amber-800 dark:text-amber-300', content)
    content = re.sub(r'\btext-amber-700(?!\s+dark:text)\b', 'text-amber-700 dark:text-amber-400', content)
    content = re.sub(r'\btext-amber-600(?!\s+dark:text)\b', 'text-amber-600 dark:text-amber-500', content)
    
    return content

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            new_content = add_dark_classes(content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f'Updated {filepath}')
