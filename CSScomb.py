import sublime, sublime_plugin,sys
from csscomb import HerokuSort, LocalSort

class BaseSorter(sublime_plugin.TextCommand):
    """Base Sorter"""

    def __init__(self, view):
        self.view = view
        self.settings = sublime.load_settings("CSScomb.sublime-settings")
        if not self.settings.has('sorter'):
            self.settings.set('sorter', 'local')
        sublime.save_settings('CSScomb.sublime-settings')

    def run(self, edit):

        selections = self.get_selections()
        SorterCall = self.get_sorter();

        threads = []
        for sel in selections:
            selbody = self.view.substr(sel)

            thread = SorterCall(sel, selbody)

            threads.append(thread)
            thread.start()

        selections.clear()
        self.handle_threads(edit, threads, selections, offset=0, i=0)

    def get_selections(self):
        selections = self.view.sel()

        # check if the user has any actual selections
        has_selections = False
        for sel in selections:
            if sel.empty() == False:
                has_selections = True

        # if not, add the entire file as a selection
        if not has_selections:
            full_region = sublime.Region(0, self.view.size())
            selections.add(full_region)

        return selections

    def get_sorter(self):
        sorter = self.settings.get('sorter', "hosted")
        sorters = {
            'heroku': HerokuSort,
            'hosted': LocalSort
        }
        return sorters[sorter] if sorter in sorters else sorters['hosted']

    def handle_threads(self, edit, threads, selections, offset = 0, i = 0):

        next_threads = []
        for thread in threads:
            if thread.is_alive():
                next_threads.append(thread)
                continue
            if thread.result == False:
                continue
            self.handle_result(edit, thread, selections, offset)
        threads = next_threads

        if len(threads):
            sublime.set_timeout(lambda: self.handle_threads(edit, threads, selections, offset, i), 100)
            return

        self.view.end_edit(edit)
        sublime.status_message('Successfully sorted')


    def handle_result(self, edit, thread, selections, offset):
        sel = thread.sel
        original = thread.original
        # print original
        result = thread.result
        # print result

        if thread.error:
            sublime.error_message(result)
            return
        elif result is None:
            sublime.error_message('There was an error sorting CSS.')
            return

        return thread


class CssSorter(BaseSorter):

    def handle_result(self, edit, thread, selections, offset):
        result = super(CssSorter, self).handle_result(edit, thread, selections, offset)

        editgroup = self.view.begin_edit('csscomb')

        sel = thread.sel
        result = thread.result
        # if offset:
            # sel = sublime.Region(thread.sel.begin() + offset, thread.sel.end() + offset)

        self.view.replace(edit, sel, result)

class ChangeSorterToLocalCommand(sublime_plugin.WindowCommand):
    def run(self, paths = []):
        self.settings = sublime.load_settings("CSScomb.sublime-settings")
        self.settings.set("sorter", "local")
        sublime.save_settings('CSScomb.sublime-settings')

class ChangeSorterToHerokuCommand(sublime_plugin.WindowCommand):
    def run(self, paths = []):
        self.settings = sublime.load_settings("CSScomb.sublime-settings")
        self.settings.set("sorter", "heroku")
        sublime.save_settings('CSScomb.sublime-settings')