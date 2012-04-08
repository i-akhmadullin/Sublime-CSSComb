import sublime, sublime_plugin,os,sys,subprocess

import subprocess

__file__ = os.path.normpath(os.path.abspath(__file__))
__path__ = os.path.dirname(__file__)
libs_path = os.path.join(__path__, 'libs')
csscomb_path = os.path.join(libs_path,"csscomb.php")

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
        except OSError, e:
            sublime.error_message('Error during sorting via CSScomb')

        #self.view.end_edit(edit)
        self.reloadFile(view, filepath)

    def reloadFile(self, view, filepath):
        view.set_scratch(True)
        #sublime.active_window().run_command("csscombrefresh")
            #viewport_position = view.viewport_position()
        view.window().run_command("close_file")
        newview = sublime.active_window().open_file(filepath)
            #newview.set_viewport_position(viewport_position, False)

# apparently can't revert view trom TextCommand
class CsscombrefreshCommand(sublime_plugin.WindowCommand):
    def run(self):
        window = sublime.active_window()
        view = window.active_view()
        view.run_command("revert")
        sublime.active_window().run_command("revert")
