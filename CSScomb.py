import sublime, sublime_plugin,sys,subprocess
from os import path

import subprocess

__file__ = path.normpath(path.abspath(__file__))
__path__ = path.dirname(__file__)
libs_path = path.join(__path__, 'libs')
csscomb_path = path.join(libs_path,"csscomb.php")

class CsscombCommand(sublime_plugin.TextCommand):

    def run(self, edit):
        view = self.view
        #self.view.begin_edit()
        filepath = view.file_name()
        view.set_status('CSScomb', 'Sorting via CSScomb started...')
        process = subprocess.Popen(['php', csscomb_path,filepath], shell=False, stdout=subprocess.PIPE)

        try:
            return_code = process.wait()
            if return_code < 0:
                sublime.error_message('Sorting probably was terminated')
            else:
                view.erase_status('CSScomb')
                sublime.status_message('Sorting via CSScomb finished succesfully.')
                sublime.set_timeout(self.reload_,250)
        except OSError, e:
            sublime.error_message('Error during sorting via CSScomb')

        #self.view.end_edit(edit)

    def reload_(self):
        self.view.run_command('revert')