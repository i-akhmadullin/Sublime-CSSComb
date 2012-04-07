import sublime, sublime_plugin,os,sys,subprocess

import subprocess

__file__ = os.path.normpath(os.path.abspath(__file__))
__path__ = os.path.dirname(__file__)
libs_path = os.path.join(__path__, 'libs')
#csscomb_path = os.path.join(libs_path,'csscomb.php')
csscomb_path = 'C:\csscomb\csscomb.php';

class CsscombCommand(sublime_plugin.TextCommand):
    def run(self, edit):
        filepath = self.view.file_name()
        subprocess.check_call('php '+csscomb_path+' '+filepath)
        self.view.window().open_file(filepath)